<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\ExpenseBeneficiary;
use App\Models\ExpenseCategory;
use App\Models\MoneySafe;
use App\Models\Product;
use App\Models\Store;
use App\Models\Employee;

use App\Models\StorePos;
use App\Models\System;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\User;
use App\Utils\CashRegisterUtil;
use App\Utils\MoneySafeUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;
    protected $productUtil;
    protected $cashRegisterUtil;
    protected $moneysafeUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil, ProductUtil $productUtil, CashRegisterUtil $cashRegisterUtil, MoneySafeUtil $moneysafeUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moneysafeUtil = $moneysafeUtil;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $expense_query = Transaction::with(['store:id,name'])
                ->leftjoin('users', 'transactions.created_by', 'users.id')
                ->leftjoin('expense_beneficiaries', 'transactions.expense_beneficiary_id', 'expense_beneficiaries.id')
                ->leftjoin('expense_categories', 'transactions.expense_category_id', 'expense_categories.id')
                ->leftjoin('employees', 'transactions.source_id', 'employees.user_id')
                ->leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
                ->where('transactions.type', 'expense');
            if (!empty(request()->expense_id)) {
                $expense_query->where('transactions.id', request()->expense_id);
            }
            if (!empty(request()->start_date)) {
                $expense_query->whereDate('transactions.transaction_date', '>=', request()->start_date);
            }
            if (!empty(request()->end_date)) {
                $expense_query->whereDate('transactions.transaction_date', '<=', request()->end_date);
            }
            if (!empty(request()->start_time)) {
                $expense_query->where('transactions.transaction_date', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->end_time)) {
                $expense_query->where('transactions.transaction_date', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
            }
            if (!empty(request()->expense_category_id)) {
                $expense_query->where('transactions.expense_category_id', request()->expense_category_id);
            }
            if (!empty(request()->expense_beneficiary_id)) {
                $expense_query->where('transactions.expense_beneficiary_id', request()->expense_beneficiary_id);
            }
            if (!empty(request()->store_id)) {
                $expense_query->where('transactions.store_id', request()->store_id);
            }
            // if (!empty(request()->store_paid_id)) {
            //     $expense_query->whereJsonContains('employees.store_id', (string)request()->store_paid_id);
            // }
            $expenses = $expense_query->select(
                'transactions.*',
                'users.name as created_by_name',
                'expense_categories.name as expense_category_name',
                'expense_beneficiaries.name as expense_beneficiary_name'
            )
                ->orderBy('transaction_date', 'desc');




            // <th>@lang('lang.expense_category')</th>
            // <th>@lang('lang.beneficiary')</th>
            // <th>@lang('lang.store')</th>
            // <th class="sum">@lang('lang.amount_paid')</th>

            // <th>@lang('lang.creation_date')</th>

            // <th>@lang('lang.created_by')</th>
            // <th>@lang('lang.payment_date')</th>



            // <th>@lang('lang.next_payment_date')</th>
            // <th>@lang('lang.store') @lang('lang.paid_by')</th>
            // <th>@lang('lang.source_of_payment')</th>
            // <th>@lang('lang.files')</th>
            // <th class="notexport">@lang('lang.action')</th>

            return DataTables::of($expenses)
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('expense_category_name', '{{$expense_category_name}}')
                ->editColumn('expense_beneficiary_name', '{{$expense_beneficiary_name}}')
                ->editColumn('final_total', '{{@num_format($final_total)}}')
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn('next_payment_date', '{{@format_datetime($next_payment_date)}}')
                ->addColumn(
                    'payment_date',
                    function ($row) {
                        $paid_on='';
                        $first=$row->transaction_payments->first();
                        if($first){
                            $paid_on  = $first->paid_on != null ? date("d/m/Y h:i A", strtotime($first->paid_on)):'';
                        }

                        return  $paid_on;
                    })
                ->addColumn(
                    'store',
                    function ($row) {
                        $store = '';
                        if (!empty($row->source_id)) {
                            $employee = Employee::where('user_id', $row->source_id)->first();
                        }
                        if (!empty($employee)) {
                            $store = implode(',', $employee->store->pluck('name')->toArray());
                        }
                        return  $store;
                    })
                ->addColumn(
                    'files',
                    function ($row) {
                        return  ' <a data-href="'. action('GeneralController@viewUploadedFiles', ['model_name' => 'Transaction', 'model_id' => $row->id, 'collection_name' => 'expense']) .'"
                                                                data-container=".view_modal"
                                                        class="btn btn-default btn-modal">'.__("lang.view").'</a>';
                    })->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('expense.expenses.view')) {
                            $html .=
                                '<li>
                                            <a href="' . action('ExpenseController@show', $row->id) . '" class="btn edit_job"><i
                                            class="fa fa-eye btn"></i> ' . __('lang.view') . '</a>
                                         </li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('expense.expenses.create_and_edit')) {
                            $html .=
                                '<li>
                                        <a href="' . action('ExpenseController@edit', $row->id) . '"><i
                                                class="dripicons-document-edit btn"></i>' . __('lang.edit') . '</a>
                                        </li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('superadmin') || auth()->user()->is_admin == 1) {
                            $html .=
                                '<li>
                                        <a data-href="' . action('ExpenseController@destroy', $row->id) . '"
                                            data-check_password="' . action('UserController@checkPassword', Auth::user()->id) . '"
                                            class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                            ' . __('lang.delete') . '</a>
                                        </li>';
                        }


                        $html .= '</ul></div>';
                        return $html;
                    })->rawColumns([
                    'payment_date',
                    'files',
                    'store',
                    'action',
                    'transaction_date',
                    'next_payment_date',
                    'final_total',
                    'created_by',
                    'expense_beneficiary_name',
                    'expense_category_name',
                ])
                ->make(true);

        }

        $expense_categories = ExpenseCategory::pluck('name', 'id');
        $expense_beneficiaries = ExpenseBeneficiary::pluck('name', 'id');
        $stores = Store::pluck('name', 'id');

        return view('expense.index')->with(compact(
            'expense_categories',
            'stores',
            'expense_beneficiaries'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::pluck('name', 'id');
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $stores = Store::getDropdown();
        $users = User::Notview()->pluck('name', 'id');

        return view('expense.create')->with(compact(
            'expense_categories',
            'payment_type_array',
            'stores',
            'users'
        ));
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


            $expense_data = [
                'grand_total' => $this->commonUtil->num_uf($data['amount']),
                'final_total' => $this->commonUtil->num_uf($data['amount']),
                'store_id' => $data['store_id'],
                'type' => 'expense',
                'status' => 'final',
                'invoice_no' => $this->productUtil->getNumberByType('expense'),
                'transaction_date' => !empty($data['transaction_date']) ? $this->commonUtil->uf_date($data['transaction_date']) : Carbon::now(),
                'expense_category_id' => $data['expense_category_id'],
                'expense_beneficiary_id' => $data['expense_beneficiary_id'],
                'next_payment_date' => !empty($data['next_payment_date']) ? $data['next_payment_date'] : null,
                'details' => !empty($data['details']) ? $data['details'] : null,
                'notify_me' => !empty($data['notify_me']) ? 1 : 0,
                'notify_before_days' => !empty($data['notify_before_days']) ? $data['notify_before_days'] : 0,
                'source_id' => !empty($data['source_id']) ? $data['source_id'] : null,
                'source_type' => !empty($data['source_type']) ? $data['source_type'] : null,
            ];
            $expense_data['created_by'] = Auth::user()->id;

            DB::beginTransaction();
            $expense = Transaction::create($expense_data);

            if ($request->has('upload_documents')) {
                foreach ($request->file('upload_documents', []) as $key => $file) {
                    $expense->addMedia($file)->toMediaCollection('expense');
                }
            }

            $payment_data = [
                'transaction_payment_id' =>  !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
                'transaction_id' =>  $expense->id,
                'amount' => $this->commonUtil->num_uf($request->amount),
                'method' => $request->method,
                'paid_on' => !empty($data['paid_on']) ? Carbon::createFromTimestamp(strtotime($data['paid_on']))->format('Y-m-d H:i:s') : Carbon::now(),
                'ref_number' => $request->ref_number,
                'card_number' => $request->card_number,
                'card_month' => $request->card_month,
                'card_year' => $request->card_year,
                'source_type' => $request->source_type,
                'source_id' => $request->source_id,
                'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $data['bank_deposit_date'] : null,
                'bank_name' => $request->bank_name,
            ];

            $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($expense, $payment_data);
            $this->transactionUtil->updateTransactionPaymentStatus($expense->id);

            if ($payment_data['method'] == 'cash') {
                $user_id = null;
                if (!empty($request->source_id)) {
                    if ($request->source_type == 'user' || $request->source_type == 'pos') {
                        if ($request->source_type == 'pos') {
                            $user_id = StorePos::where('id', $request->source_id)->first()->user_id;
                        }
                        if ($request->source_type == 'user') {
                            $user_id = $request->source_id;
                        }
                        $this->cashRegisterUtil->addPayments($expense, $payment_data, 'debit', $user_id);
                    }
                    if ($request->source_type == 'safe') {
                        $money_safe = MoneySafe::find($request->source_id);
                        $payment_data['currency_id'] = System::getProperty('currency');
                        $this->moneysafeUtil->addPayment($expense, $payment_data, 'debit', $transaction_payment->id, $money_safe);
                    }
                }
            }

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang.expense_added')
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Transaction::find($id);

        return view('expense.show')->with(compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Transaction::find($id);
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $expense_categories = ExpenseCategory::pluck('name', 'id');
        $expense_beneficiaries = ExpenseBeneficiary::where('expense_category_id', $expense->expense_category_id)->pluck('name', 'id');
        $stores = Store::getDropdown();
        $users = User::Notview()->pluck('name', 'id');

        return view('expense.edit')->with(compact(
            'expense',
            'stores',
            'users',
            'payment_type_array',
            'expense_beneficiaries',
            'expense_categories'
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
        // try {
        $data = $request->except('_token', '_method', 'submit');

        $expense = Transaction::where('id', $id)->first();

        $expense_data = [
            'grand_total' => $this->commonUtil->num_uf($data['amount']),
            'final_total' => $this->commonUtil->num_uf($data['amount']),
            'transaction_date' => !empty($data['transaction_date']) ? $this->commonUtil->uf_date($data['transaction_date']) : $expense->transaction_date,
            'expense_category_id' => $data['expense_category_id'],
            'expense_beneficiary_id' => $data['expense_beneficiary_id'],
            'next_payment_date' => !empty($data['next_payment_date']) ? $data['next_payment_date'] : null,
            'details' => !empty($data['details']) ? $data['details'] : null,
            'notify_me' => !empty($data['notify_me']) ? 1 : 0,
            'notify_before_days' => !empty($data['notify_before_days']) ? $data['notify_before_days'] : 0,
            'source_id' => !empty($data['source_id']) ? $data['source_id'] : null,
            'source_type' => !empty($data['source_type']) ? $data['source_type'] : null,
        ];
        $expense_data['created_by'] = Auth::user()->id;
        DB::beginTransaction();
        $expense->update($expense_data);

        if ($request->has('upload_documents')) {
            foreach ($request->file('upload_documents', []) as $key => $file) {
                $expense->addMedia($file)->toMediaCollection('expense');
            }
        }

        $payment_data = [
            'transaction_payment_id' =>  !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
            'transaction_id' =>  $expense->id,
            'amount' => $this->commonUtil->num_uf($request->amount),
            'method' => $request->method,
            'paid_on' => !empty($data['paid_on']) ? Carbon::createFromTimestamp(strtotime($data['paid_on']))->format('Y-m-d H:i:s') : Carbon::now(),
            'ref_number' => $request->ref_number,
            'card_number' => $request->card_number,
            'card_month' => $request->card_month,
            'card_year' => $request->card_year,
            'source_type' => $request->source_type,
            'source_id' => $request->source_id,
            'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $data['bank_deposit_date'] : null,
            'bank_name' => $request->bank_name,
        ];
        $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($expense, $payment_data);
        $this->transactionUtil->updateTransactionPaymentStatus($expense->id);

        if ($payment_data['method'] == 'cash') {
            $user_id = null;
            if (!empty($request->source_id)) {
                if ($request->source_type == 'pos') {
                    $user_id = StorePos::where('id', $request->source_id)->first()->user_id;
                }
                if ($request->source_type == 'user') {
                    $user_id = $request->source_id;
                }

                if ($request->source_type == 'safe') {
                    $money_safe = MoneySafe::find($request->source_id);
                    $payment_data['currency_id'] = System::getProperty('currency');
                    $this->moneysafeUtil->updatePayment($expense, $payment_data, 'debit', $transaction_payment->id, null, $money_safe);
                }
            }
            if ($request->source_type == 'user' || $request->source_type == 'pos') {
                $cr_transaction = CashRegisterTransaction::where('transaction_id', $expense->id)->first();
                if (!empty($request->cash_register_id)) {
                    $register = CashRegister::where('id', $request->cash_register_id)->first();
                    if (!empty($register->closed_at)) {
                        $register->closing_amount = $register->closing_amount - $this->commonUtil->num_uf($request->amount);
                        $register->save();
                    }
                    $pre_register = CashRegister::where('id', $cr_transaction->cash_register_id)->first();
                    if (!empty($pre_register->closed_at)) {
                        $pre_register->closing_amount = $pre_register->closing_amount + $this->commonUtil->num_uf($request->amount);
                        $pre_register->save();
                    }
                } else {
                    $register = CashRegister::where('id', $cr_transaction->cash_register_id)->first();
                }

                $this->cashRegisterUtil->updateCashRegisterTransaction($cr_transaction->id, $register, $payment_data['amount'], $expense->type, 'debit', $user_id, '', null);
            }
        }

        DB::commit();

        $output = [
            'success' => true,
            'msg' => __('lang.expense_updated')
        ];
        // } catch (\Exception $e) {
        //     Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
        //     $output = [
        //         'success' => false,
        //         'msg' => __('lang.something_went_wrong')
        //     ];
        // }

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

            Transaction::where('id', $id)->delete();
            TransactionPayment::where('transaction_id', $id)->delete();
            CashRegisterTransaction::where('transaction_id', $id)->delete();

            $output = [
                'success' => true,
                'msg' => __('lang.expense_deleted')
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
}
