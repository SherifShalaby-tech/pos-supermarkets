<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DebtPayment;
use App\Models\DebtTransactionPayment;
use App\Models\MoneySafe;
use App\Models\StorePos;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\User;
use App\Utils\CashRegisterUtil;
use App\Utils\MoneySafeUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionPaymentController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;
    protected $cashRegisterUtil;
    protected $moneysafeUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @param CashRegisterUtil $cashRegisterUtil
     * @param MoneySafeUtil $moneysafeUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil, CashRegisterUtil $cashRegisterUtil, MoneySafeUtil $moneysafeUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moneysafeUtil = $moneysafeUtil;
    }

    /**
     * addPayment
     *
     * @param integer $transaction_id
     * @return void
     */
    public function addPayment($transaction_id)
    {

        
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $transaction = Transaction::find($transaction_id);
        $users = User::Notview()->pluck('name', 'id');
        $balance = $this->transactionUtil->getCustomerBalanceExceptTransaction($transaction->customer_id,$transaction_id)['balance'];
        $finalTotal = $transaction->final_total;
        $transactionPaymentsSum = $transaction->transaction_payments->sum('amount');
        if ($balance > 0 && $balance < $finalTotal - $transactionPaymentsSum) {
            if (isset($transaction->return_parent)) {
                $amount = $finalTotal - $transactionPaymentsSum - $transaction->return_parent->final_total - $balance;
            } else {
                $amount = $finalTotal - $transactionPaymentsSum - $balance;
            }
        } else {
            if (isset($transaction->return_parent)) {
                $amount = $finalTotal - $transactionPaymentsSum - $transaction->return_parent->final_total;
            } else {
                $amount = $finalTotal - $transactionPaymentsSum;
            }
        }
        return view('transaction_payment.add_payment')->with(compact(
            'payment_type_array',
            'transaction_id',
            'transaction',
            'users',
            'balance',
            'amount'
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');


            $payment_data = [
                'transaction_payment_id' =>  !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
                'transaction_id' =>  $request->transaction_id,
                'amount' => $this->commonUtil->num_uf($request->amount),
                'method' => $request->method,
                'paid_on' => $this->commonUtil->uf_date($data['paid_on']) . ' ' . date('H:i:s'),
                'ref_number' => $request->ref_number,
                'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->commonUtil->uf_date($data['bank_deposit_date']) : null,
                'bank_name' => $request->bank_name,
                'card_number' => $request->card_number,
                'card_month' => $request->card_month,
                'card_year' => $request->card_year,
            ];
            DB::beginTransaction();
            $transaction = Transaction::find($request->transaction_id);
            $debt_data = [
                'amount' => $this->commonUtil->num_uf($request->amount),
                'type' => 'Debt',
                'customer_id' => !empty( $transaction->customer_id) ?  $transaction->customer_id :  auth()->id(),
                'method' => $request->method,
                'paid_on' => $this->commonUtil->uf_date($request->paid_on),
                'ref_number' => $request->ref_number,
                'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
                'bank_name' => $request->bank_name,
                'created_by' => auth()->id(),
            ];
            $debt_payment=  DebtPayment::create($debt_data);
            $customer = Customer::find($transaction->customer_id);
            if($request->add_to_customer_balance > 0){
                $customer->added_balance = $customer->added_balance + $request->add_to_customer_balance;
                $customer->save();
            }
           
            if ($request->upload_documents) {
                foreach ($request->file('upload_documents', []) as $key => $doc) {
                    $debt_payment->addMedia($doc)->toMediaCollection('debt_payment');
                }
            }
            $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);
            DebtTransactionPayment::create([
                'debt_payment_id'=>$debt_payment->id,
                'transaction_payment_id'=>$transaction_payment->id,
                'amount'=>$this->commonUtil->num_uf($request->amount),
            ]);
            if ($request->upload_documents) {
                foreach ($request->file('upload_documents', []) as $key => $doc) {
                    $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                }
            }

            if ($transaction->type == 'add_stock' && $payment_data['method'] == 'cash') {
                $user_id = null;
                if (!empty($request->source_id)) {
                    if ($request->source_type == 'pos') {
                        $user_id = StorePos::where('id', $request->source_id)->first()->user_id;
                    }
                    if ($request->source_type == 'user') {
                        $user_id = $request->source_id;
                    }
                    if (!empty($user_id)) {
                        $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'debit', $user_id);
                    }

                    if ($request->source_type == 'safe') {
                        $money_safe = MoneySafe::find($request->source_id);
                        $payment_data['currency_id'] = $transaction->paying_currency_id;
                        $this->moneysafeUtil->updatePayment($transaction, $payment_data, 'debit', $transaction_payment->id, null, $money_safe);
                    }
                }
            }


            $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);
            if ($transaction->type == 'sell') {
                $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'credit', null, $transaction_payment->id);

                if ($payment_data['method'] == 'bank_transfer' || $payment_data['method'] == 'card') {
                    $this->moneysafeUtil->addPayment($transaction, $payment_data, 'credit', $transaction_payment->id);
                }
            }
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        if (request()->ajax()) {
            return $output;
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $transaction_id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $payment_type_array = $this->commonUtil->getPaymentTypeArrayForPos();
        return view('transaction_payment.show')->with(compact(
            'transaction',
            'payment_type_array'
        ));
    }
    /**
     * Display the specified resource.
     *
     * @param  string  $gift_card_number
     * @return \Illuminate\Http\Response
     */
    public function showMethodGiftCard($gift_card_number)
    {

        $transaction_payments=TransactionPayment::with('transaction')
            ->where('gift_card_number',$gift_card_number)->get();

        $payment_type_array = $this->commonUtil->getPaymentTypeArrayForPos();
        return view('gift_card.show')->with(compact(
            'transaction_payments',
            'payment_type_array'
        ));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $transaction_payment_id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = TransactionPayment::find($id);
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();

        return view('transaction_payment.edit')->with(compact(
            'payment',
            'payment_type_array'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->except('_token');
            $transaction_payment = TransactionPayment::find($id);
            $old_tp = $transaction_payment;
            $transaction_id = $transaction_payment->transaction_id;

            $payment_data = [
                'transaction_payment_id' =>  $id,
                'amount' => $this->commonUtil->num_uf($request->amount),
                'method' => $request->method,
                'paid_on' => $this->commonUtil->uf_date($data['paid_on']) . ' ' . date('H:i:s'),
                'ref_number' => $request->ref_number,
                'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $data['bank_deposit_date'] : null,
                'bank_name' => $request->bank_name,
            ];
            $transaction = Transaction::find($transaction_id);

            $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);
            $debt_data = [
                'amount' => $this->commonUtil->num_uf($request->amount),
                'method' => $request->method,
                'paid_on' => $this->commonUtil->uf_date($request->paid_on),
                'ref_number' => $request->ref_number,
                'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
                'bank_name' => $request->bank_name,
                'created_by' => auth()->id(),
            ];
            $DebtTransactionPayment= DebtTransactionPayment::where('transaction_payment_id',$transaction_payment->id)->first();
            if($DebtTransactionPayment){
                $DebtTransactionPayment->amount=$this->commonUtil->num_uf($request->amount);
                $DebtTransactionPayment->save();
                $transaction_payment_d= DebtPayment::where('id',$transaction_payment->debt_payment_id)
                    ->update($debt_data);
            }else{
                $debt_data['type'] = 'Debt';
                $debt_data['customer_id'] = $transaction->customer_id;
                $debt_payment=  DebtPayment::create($debt_data);
                if ($request->upload_documents) {
                    foreach ($request->file('upload_documents', []) as $key => $doc) {
                        $debt_payment->addMedia($doc)->toMediaCollection('debt_payment');
                    }
                }
                DebtTransactionPayment::create([
                    'debt_payment_id'=>$debt_payment->id,
                    'transaction_payment_id'=>$transaction_payment->id,
                    'amount'=>$this->commonUtil->num_uf($request->amount),
                ]);
            }


            if ($request->upload_documents) {
                foreach ($request->file('upload_documents', []) as $key => $doc) {
                    $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                    $transaction_payment_d->addMedia($doc)->toMediaCollection('debt_payment');
                }
            }
            $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);

            if ($transaction->type == 'sell') {
                $payments[] = $payment_data;
                $this->cashRegisterUtil->updateSellPayments($transaction, $payments);
                $this->moneysafeUtil->updatePayment($transaction, $payment_data, 'credit', $transaction_payment->id, $old_tp);
            }

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $transaction_payment = TransactionPayment::find($id);
            $transaction_id = $transaction_payment->transaction_id;
            $transaction_payment->delete();

            $this->transactionUtil->updateTransactionPaymentStatus($transaction_id);

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * get the modal of customer pay due
     *
     * @param int $customer_id
     * @return void
     */
    public function getCustomerDue($customer_id, $extract_due = 'false')
    {
        $customer = Customer::find($customer_id);

        $due = abs(app('App\Http\Controllers\CustomerController')->getCustomerBalance($customer_id)['balance']);
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();

        return view('transaction_payment.pay_customer_due')->with(compact(
            'payment_type_array',
            'due',
            'customer',
            'extract_due'
        ));
    }

    /**
     * pay the customer due amounts
     *
     * @param int $customer_id
     * @return void
     */
    public function payCustomerDue(Request $request, $customer_id)
    {
        try {
            $amount = $this->commonUtil->num_uf($request->amount);
            $customer = Customer::find($customer_id);
            if($request->add_to_customer_balance > 0){
                $customer->added_balance = $customer->added_balance + $request->add_to_customer_balance;
                $customer->save();
            }
            if($this->commonUtil->num_uf($request->balance)<$amount && $request->extract_due == 'true'){
                DB::commit();
                $output = [
                    'success' => false,
                    'msg' => __('lang.amount_greater_than_balance')
                ];
            }
                $transactions = Transaction::where('customer_id', $customer_id)->where('type', 'sell')->whereIn('payment_status', ['pending', 'partial'])->orderBy('transaction_date', 'asc')->get();

                DB::beginTransaction();
                $remaining_due_amount=0;
                foreach ($transactions as $transaction) {
                    $due_for_transaction = $this->getDueForTransaction($transaction->id);
                    $paid_amount = 0;
                    if ($amount > 0) {
                        if ($amount >= $due_for_transaction) {
                            $paid_amount = $due_for_transaction;
                            $amount -= $due_for_transaction;
                        } else if ($amount < $due_for_transaction) {
                            $paid_amount = $amount;
                            $amount = 0;
                        }
                        $remaining_due_amount+=$paid_amount;
                        $payment_data = [
                            'transaction_payment_id' =>  !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
                            'transaction_id' =>  $transaction->id,
                            'amount' => $paid_amount,
                            'method' => $request->method,
                            'paid_on' => $this->commonUtil->uf_date($request->paid_on),
                            'ref_number' => $request->ref_number,
                            'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
                            'bank_name' => $request->bank_name,
                        ];

                        $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);
                        $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);
                        $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'credit');

                        if ($request->upload_documents) {
                            foreach ($request->file('upload_documents', []) as $key => $doc) {
                                $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                            }
                        }
                    }
                }
                if ($request->extract_due == 'true') {
                    //update customer deposit balance if any
                    if($this->commonUtil->num_uf($request->amount)==($customer->added_balance-$remaining_due_amount)){
                        $customer->added_balance = $customer->added_balance-$this->commonUtil->num_uf($request->amount)-$remaining_due_amount;
                        $customer->save();
                        DB::commit();
                        $output = [
                            'success' => true,
                            'msg' => __('lang.success')
                        ];
                    }elseif($this->commonUtil->num_uf($request->amount)<($customer->added_balance-$remaining_due_amount)){
                        $customer->added_balance = $customer->added_balance-($this->commonUtil->num_uf($request->amount)-$remaining_due_amount);
                        $customer->save();
                        DB::commit();
                        $output = [
                            'success' => true,
                            'msg' => __('lang.success')
                        ];
                    }
                    else{
                        DB::commit();
                        $output = [
                            'success' => false,
                            'msg' => __('lang.amount_greater_than_balance')
                        ];
                    }
                }else{
                    DB::commit();
                    $output = [
                        'success' => true,
                        'msg' => __('lang.success')
                    ];
                }
            
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        if (request()->ajax()) {
            return $output;
        }

        return redirect()->back()->with('status', $output);
    }
    // public function payCustomerDue(Request $request, $customer_id)
    // {
    //     try {
    //         $amount = $this->commonUtil->num_uf($request->amount);
    //         $transactions = Transaction::where('customer_id', $customer_id)->whereIn('type', ['sell','addToBalance'])->whereIn('payment_status', ['pending', 'partial'])->orderBy('transaction_date', 'asc')->get();
    //         DB::beginTransaction();
    //         $debt_data = [
    //             'amount' => $amount,
    //             'type' => 'Debt',
    //             'customer_id' => $request->customer_id,
    //             'method' => $request->method,
    //             'paid_on' => $this->commonUtil->uf_date($request->paid_on),
    //             'ref_number' => $request->ref_number,
    //             'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
    //             'bank_name' => $request->bank_name,
    //             'created_by' => auth()->id(),
    //         ];
    //         $debt_payment=  DebtPayment::create($debt_data);
    //         if ($request->upload_documents) {
    //             foreach ($request->file('upload_documents', []) as $key => $doc) {
    //                 $debt_payment->addMedia($doc)->toMediaCollection('debt_payment');
    //             }
    //         }
    //         $debt_payment_transactions=[];
    //         foreach ($transactions as $transaction) {
    //             $due_for_transaction = $this->getDueForTransaction($transaction->id);
    //             $paid_amount = 0;
    //             if ($amount > 0) {
    //                 if ($amount >= $due_for_transaction) {
    //                     $paid_amount = $due_for_transaction;
    //                     $amount -= $due_for_transaction;
    //                 } else if ($amount < $due_for_transaction) {
    //                     $paid_amount = $amount;
    //                     $amount = 0;
    //                 }

    //                 $payment_data = [
    //                     'transaction_payment_id' =>  !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
    //                     'transaction_id' =>  $transaction->id,
    //                     'amount' => $paid_amount,
    //                     'method' => $request->method,
    //                     'paid_on' => $this->commonUtil->uf_date($request->paid_on),
    //                     'ref_number' => $request->ref_number,
    //                     'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
    //                     'bank_name' => $request->bank_name,
    //                 ];

    //                 $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);
    //                 if($transaction_payment){
    //                     DebtTransactionPayment::create([
    //                         'debt_payment_id'=>$debt_payment->id,
    //                         'transaction_payment_id'=>$transaction_payment->id,
    //                         'amount'=>$paid_amount,
    //                     ]);
    //                     $debt_payment_transactions[$transaction_payment->id]=$paid_amount;
    //                     $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);
    //                     $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'credit');

    //                     if ($request->upload_documents) {
    //                         foreach ($request->file('upload_documents', []) as $key => $doc) {
    //                             $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         DB::commit();
    //         $output = [
    //             'success' => true,
    //             'msg' => __('lang.success')
    //         ];
    //     } catch (\Exception $e) {
    //         Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
    //         $output = [
    //             'success' => false,
    //             'msg' => __('lang.something_went_wrong')
    //         ];
    //     }
    //     if (request()->ajax()) {
    //         return $output;
    //     }

    //     return redirect()->back()->with('status', $output);
    // }

    /**
     * calculate the amount due for transaction
     *
     * @param int $transaction_id
     * @return float
     */
    public function getDueForTransaction($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $total_paid = Transaction::leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
            ->where('transactions.id', $transaction_id)
            ->sum('amount');

        return $transaction->final_total - $total_paid;
    }
}
