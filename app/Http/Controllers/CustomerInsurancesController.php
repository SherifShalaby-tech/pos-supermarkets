<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerInsurance\Received;
use App\Http\Requests\CustomerInsurance\Store;
use App\Models\Customer;
use App\Models\DepositSafe;
use App\Models\ItemBorrowed;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerInsurancesController extends Controller
{
    public function index(){
        try{
            $customers = Customer::get(['id','name']);
            $products = ItemBorrowed::get(['id','name']);
            $deposits = DepositSafe::with(['customer' => function($c){
               $c->select('id','name','mobile_number');
            },'product' => function($p){
                $p->select('id','name');
            }])
                ->get();
            return view('customer.customer_insurances.index',compact('deposits','customers','products'));
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function create(){
        try{
            $products = ItemBorrowed::get(['id','name']);
            $clients = Customer::get(['id','name']);
            return view('customer.customer_insurances.create',compact('products','clients'));
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function store(Store $request){
        try{
            $request->validated();
            $deposit = DB::table('deposit_safes')->insert([
                'product_id' => $request->item_id,
                'customer_id' => $request->customer_id,
                'status' => $request->status,
                'deposit_amount' => $request->insurance_amount,
                'admin_id' => $request->admin_id,
                'return_date' => $request->return_date
            ]);
            return redirect(route('customer-insurances.index'));
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function edit($id){
        try{
            $deposit = DepositSafe::where('id',$id)->first();
            $products = Product::get(['id','name']);
            $clients = Customer::get(['id','name']);
            return view('customer.customer_insurances.edit',compact('products','clients','deposit'));
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try{
            $deposit = DepositSafe::where('id',$request->id)->update([
                'product_id' => $request->item_id,
                'customer_id' => $request->customer_id,
                'status' => $request->status,
                'deposit_amount' => $request->insurance_amount,
                'return_date' => $request->return_date
            ]);
            return redirect()->back();
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id){
        $deposit = DepositSafe::where('id',$id)->delete();
        $output = [
            'success' => true,
            'msg' => __('lang.deposit_deleted')
        ];
        return $output;
    }

    public function received(Received $request){
        try{
            DB::beginTransaction();
            $request->validated();
            $deposit = DepositSafe::where('id',$request->id)->first();
            if($deposit){
                $reaming_amount = '';
                $deposit->penalties = $request->penalties;
                $deposit->cause_the_penalties = $request->cause_the_penalties;
                $deposit->penalty_amount = $request->penalty_amount;
                if($request->penalty_amount){
                    $reaming_amount =  $deposit->deposit_amount - $request->penalty_amount;
                    $deposit->deposit_amount = $reaming_amount;
                    $customer = Customer::where('id',$deposit->customer_id)->update(['deposit_balance' => $reaming_amount]);
                }
                $deposit->save();
                DB::commit();
                return redirect()->back();
            }
            return redirect()>back()->with(['error' => 'not found']);
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

}
