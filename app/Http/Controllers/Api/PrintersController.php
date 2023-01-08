<?php

namespace App\Http\Controllers\Api;

use App\Models\System;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class PrintersController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $PrintersArray = [];
        $transaction = "12547";
        $payment_types = "Debit";
        $print_gift_invoice = request()->print_gift_invoice;
        $invoice_lang = System::getProperty('invoice_lang');
        if (empty($invoice_lang)) {
            $invoice_lang = request()->session()->get('language');
        }



       // $product = Printer::where(['is_active' => 1 , 'is_kitchen' =>  1, 'is_cashier' =>  0])->get(['name']);
       $printers = Printer::where(['is_active' => 1])->get();
       foreach($printers as $printer) {
         if($printer->is_kitchen == 1 && $printer->is_cashier == 0) {
            $PrintersArray[] = $printer->name;
            $html_content = view('sale_pos.partials.invoice_kitchen_api')->with(compact(
                'transaction',
                'payment_types',
                'invoice_lang',
                'print_gift_invoice'
            ))->render();
         }
         if($printer->is_kitchen == 0 && $printer->is_cashier == 1) {
            $PrintersArray[] = $printer->name;
            $html_content = view('sale_pos.partials.invoice_api')->with(compact(
                'transaction',
                'payment_types',
                'invoice_lang',
                'print_gift_invoice'
            ))->render();
         }
       }

        $data = ["name"=>$PrintersArray , "html"=>[$html_content]];
        return $this->handleResponse($data, 'Printers have been retrieved!');
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
