<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemBorrowed\Give;
use App\Http\Requests\ItemBorrowed\Store;
use App\Models\Customer;
use App\Models\DepositSafe;
use App\Models\ItemBorrowed;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemBorrowedController extends Controller
{
    public function index(){
        try{
            $products = ItemBorrowed::with(['customer' => function($c){
                $c->select('id','name','mobile_number');
            }])
                ->orderBy('id','desc')
                ->get();
            $clients = Customer::get(['id','name']);

            return view('ItemBorrowed.index',compact('products','clients'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

    public function create(){
        $products = Product::get(['id','name']);
        $clients = Customer::get(['id','name']);
        return view('ItemBorrowed.create',compact('products','clients'));
    }

    public function store(Store $request){
        try{
            $request->validated();
            $itemCreate = ItemBorrowed::create([
               'name' => $request->name,
               'deposit_amount' => $request->deposit_amount,
               'admin_id' => $request->admin_id
            ]);
            return redirect(route('item-borrowed.index'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

    public function update(Request $request){
        try{
            $item = ItemBorrowed::where('id',$request->id)->update([
               'name' => $request->name,
               'deposit_amount' => $request->deposit_amount
            ]);
            return redirect()->back();
        }catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

    public function give(Give $request){
        try{
            $request->validated();
            $item = ItemBorrowed::where('id',$request->id)->first();
            $item->deposit_amount = $request->deposit_amount;
            $item->return_date = $request->return_date;
            $item->customer_id = $request->customer_id;
            $item->save();
            return redirect()->back();
        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id){
        $item = ItemBorrowed::where('id',$id)->delete();
        $output = [
            'success' => true,
            'msg' => __('lang.deposit_deleted')
        ];
        return $output;
    }
}
