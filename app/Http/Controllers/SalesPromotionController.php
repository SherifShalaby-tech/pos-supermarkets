<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\SalesPromotion;
use App\Models\Store;
use App\Models\Variation;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesPromotionController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $productUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales_promotions = SalesPromotion::with('variations:id,product_id,name','condition_variations:id,product_id,name')->get();
        $stores = Store::getDropdown();

        return view('sales_promotion.index')->with(compact(
            'sales_promotions',
            'stores'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $stores = Store::getDropdown();
        $customer_types  = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');

        return view('sales_promotion.create')->with(compact(
            'stores',
            'customer_types',
        ));
    }

    /**
     * store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['type' => ['required', 'max:255']],
            ['store_ids' => ['required', 'max:255']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['discount_type' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );

        try {
            $data['name'] = $request->name;
            $data['type'] = $request->type;
            $data['start_date'] = $request->start_date ?? null;
            $data['end_date'] = $request->end_date ?? null;
            $data['store_ids'] = $request->store_ids;
            $data['customer_type_ids'] = $request->customer_type_ids;
            $data['code'] = $this->commonUtil->randString(5, 'SP');
            $data['created_by'] = Auth::user()->id;
            $data['product_condition'] = !empty($request->product_condition) ? 1 : 0;
            $data['purchase_condition'] = !empty($request->purchase_condition) ? 1 : 0;
            $data['generate_barcode'] = !empty($request->generate_barcode) ? 1 : 0;
            $data['discount_value'] = !empty($request->discount_value) ? $this->productUtil->num_uf($request->discount_value) : 0;
            $data['discount_type'] = !empty($request->discount_type) ? $request->discount_type : 'fixed';
            $data['actual_sell_price'] = !empty($request->actual_sell_price) ? $this->productUtil->num_uf($request->actual_sell_price) : 0;
            $data['purchase_condition_amount'] = !empty($request->purchase_condition_amount) ? $this->productUtil->num_uf($request->purchase_condition_amount) : 0;
            $data['product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($request->pct); //product ids to get the discount
            $data['condition_product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($request->pci); //product ids condition to get the discount
            $data['pct_data'] = $request->pct ?? [];
            $data['pci_data'] = $request->pci ?? []; //product condition items
            $data['package_promotion_qty'] = $request->package_promotion_qty ?? []; //package promotion qty condition
            DB::beginTransaction();
            SalesPromotion::create($data);


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

        return redirect()->to('sales-promotion')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sales_promotion = SalesPromotion::find($id);

        return view('sales_promotion.show')->with(compact(
            'sales_promotion'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sales_promotion = SalesPromotion::find($id);
        $stores = Store::getDropdown();
        $customer_types  = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');

        $product_details = $this->productUtil->getProductDetailsUsingArrayIds($sales_promotion->product_ids, $sales_promotion->store_ids,true);

        return view('sales_promotion.edit')->with(compact(
            'sales_promotion',
            'stores',
            'customer_types',
            'product_details',
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
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['type' => ['required', 'max:255']],
            ['store_ids' => ['required', 'max:255']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['discount_type' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );
//        dd($request->all());

        try {
            $data['name'] = $request->name;
            $data['type'] = $request->type;
            $data['start_date'] = $request->start_date ?? null;
            $data['end_date'] = $request->end_date ?? null;
            $data['store_ids'] = $request->store_ids;
            $data['customer_type_ids'] = $request->customer_type_ids;
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $data['store_ids'] = $request->store_ids;
            $data['product_condition'] = !empty($request->product_condition) ? 1 : 0;
            $data['purchase_condition'] = !empty($request->purchase_condition) ? 1 : 0;
            $data['generate_barcode'] = !empty($request->generate_barcode) ? 1 : 0;
            $data['discount_value'] = !empty($request->discount_value) ? $this->productUtil->num_uf($request->discount_value) : 0;
            $data['discount_type'] = !empty($request->discount_type) ? $request->discount_type : 'fixed';
            $data['actual_sell_price'] = !empty($request->actual_sell_price) ? $this->productUtil->num_uf($request->actual_sell_price) : 0;
            $data['purchase_condition_amount'] = !empty($request->purchase_condition_amount) ? $this->productUtil->num_uf($request->purchase_condition_amount) : 0;
            $data['product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($request->pct); //product ids to get the discount
            $data['condition_product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($request->pci); //product ids condition to get the discount
            $data['pct_data'] = $request->pct ?? [];
            $data['pci_data'] = $request->pci ?? []; //product condition items
            $data['package_promotion_qty'] = $request->package_promotion_qty ?? []; //package promotion qty condition

            DB::beginTransaction();

            SalesPromotion::where('id', $id)->update($data);


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
            SalesPromotion::find($id)->delete();
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
     * get product details
     *
     * @param int $id
     * @return void
     */
    public function getProductDetailsRows(Request $request)
    {
        $store_ids = $request->store_ids;
        $type = $request->type;
        $array = $request->array;
        $is_edit=$request->is_edit == '1'?1:0;
        if($request->product_array_old_ids){
            $array=array_diff($array,$request->product_array_old_ids);
        }
        $products = $this->productUtil->getProductDetailsUsingArrayIds($array, $store_ids,true);
        return view('sales_promotion.partials.product_details_row')->with(compact(
            'products',
            'is_edit',
            'type'
        ));
    }

    /**
     * get the resource details
     *
     * @param int $id
     * @return void
     */
    public function getSalePromotionDetails($id)
    {
        $sales_promotion = SalesPromotion::find($id);

        $data_array = [];
        if (!empty($sales_promotion)) {
            $i = 0;
            $product_ids = $sales_promotion->product_ids;
            foreach ($product_ids as $product_id) {
                $variation_id = Variation::where('product_id', $product_id)->first()->id;
                $data_array[$i]['product_id'] = $product_id;
                $data_array[$i]['variation_id'] = $variation_id;
                $data_array[$i]['qty'] = (int)$sales_promotion->package_promotion_qty[$product_id];
                $i++;
            }
        }
        if ($sales_promotion->product_condition) {
            $condition_product_ids = $sales_promotion->condition_product_ids;
            foreach ($condition_product_ids as $c_product_id) {
                $variation_id = Variation::where('product_id', $c_product_id)->first()->id;
                $data_array[$i]['product_id'] = $c_product_id;
                $data_array[$i]['variation_id'] = $variation_id;
                $data_array[$i]['qty'] = 1;
                $i++;
            }
        }

        return $data_array;
    }
    public function getProductConditionTree()
    {
        $products =      Product::orderBy('name', 'asc')->pluck('name', 'id');
        $product_classes = ProductClass::get();
        $pct_data=[];
        $pci_data=[];
        if(\request()->sales_promotion_id != null ){
            $sales_promotion = SalesPromotion::find(\request()->sales_promotion_id);
            $pct_data = $sales_promotion->pct_data;
            $pci_data = $sales_promotion->pci_data;
        }
        $tree_html=view('sales_promotion.partials.product_condition_tree_modal_body')->with(compact(
            'products',
            'pci_data',
            'pct_data',
            'product_classes'
        ))->render();
        return $tree_html;
    }
    public function getProductSelectionTree()
    {
        $pct_data=[];
        $pci_data=[];
        if(\request()->sales_promotion_id != null ){
            $sales_promotion = SalesPromotion::find(\request()->sales_promotion_id);
            $pct_data = $sales_promotion->pct_data;
            $pci_data = $sales_promotion->pci_data;
        }
        $products =      Product::orderBy('name', 'asc')->pluck('name', 'id');
        $product_classes = ProductClass::get();

        $tree_html=view('product_classification_tree.partials.product_selection_tree_modal_body')->with(compact(
            'products',
            'pci_data',
            'pct_data',
            'product_classes'
        ))->render();
        return $tree_html;
    }
}
