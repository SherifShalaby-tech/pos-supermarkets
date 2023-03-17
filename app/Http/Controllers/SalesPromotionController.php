<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Grade;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\SalesPromotion;
use App\Models\Size;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Models\Variation;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

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
        $suppliers = Supplier::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $po_nos = Transaction::where('type', 'purchase_order')->where('status', '!=', 'received')->pluck('po_no', 'id');
        $status_array = $this->commonUtil->getPurchaseOrderStatusArray();
        $payment_status_array = $this->commonUtil->getPaymentStatusArray();
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $payment_types = $payment_type_array;
        $taxes = Tax::pluck('name', 'id');

        $variation_id = request()->get('variation_id');
        $product_id = request()->get('product_id');

        $is_raw_material = request()->segment(1) == 'raw-material' ? true : false;

        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes_array = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $exchange_rate_currencies = $this->commonUtil->getCurrenciesExchangeRateArray(true);

        $users = User::Notview()->pluck('name', 'id');
        return view('sales_promotion.create')->with(compact(
            'stores',
            'customer_types',
            'is_raw_material',
            'suppliers',
            'status_array',
            'payment_status_array',
            'payment_type_array',
            'variation_id',
            'product_id',
            'po_nos',
            'taxes',
            'product_classes',
            'payment_types',
            'payment_status_array',
            'categories',
            'sub_categories',
            'brands',
            'units',
            'colors',
            'sizes',
            'grades',
            'taxes_array',
            'customer_types',
            'exchange_rate_currencies',
            'discount_customer_types',
            'users',
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
            ['product_variation_id' => ['required','array', 'exits:variations,id']],
            ['product_variation_id.*' => ['required','integer', 'exits:variations,id']],
            ['product_condition_variation_id' => ['nullable','array']],
            ['product_condition_variation_id.*' => ['nullable','integer', 'exits:variations,id']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['discount_type' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );

        try {

            $product_variation_id['product_selected']=$request->product_variation_id;
            $product_condition_variation_id['product_selected']=$request->product_condition_variation_id;

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
            $data['product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($product_variation_id); //product ids to get the discount
            $data['condition_product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($product_condition_variation_id); //product ids condition to get the discount
            $data['pct_data'] = $request->product_variation_id ?? [];
            $data['pci_data'] = $request->product_condition_variation_id ?? []; //product condition items
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
        $product_details = $this->productUtil->getProductDetailsUsingArrayIds($sales_promotion->product_ids, $sales_promotion->store_ids,true);
        $condition_products = $this->productUtil->getProductDetailsUsingArrayIds($sales_promotion->condition_product_ids, $sales_promotion->store_ids,true);
        $stores = Store::getDropdown();
        $suppliers = Supplier::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $po_nos = Transaction::where('type', 'purchase_order')->where('status', '!=', 'received')->pluck('po_no', 'id');
        $status_array = $this->commonUtil->getPurchaseOrderStatusArray();
        $payment_status_array = $this->commonUtil->getPaymentStatusArray();
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $payment_types = $payment_type_array;
        $taxes = Tax::pluck('name', 'id');

        $variation_id = request()->get('variation_id');
        $product_id = request()->get('product_id');

        $is_raw_material = request()->segment(1) == 'raw-material' ? true : false;

        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes_array = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $exchange_rate_currencies = $this->commonUtil->getCurrenciesExchangeRateArray(true);

        $users = User::Notview()->pluck('name', 'id');

        return view('sales_promotion.edit')->with(compact(
            'sales_promotion',
            'stores',
            'customer_types',
            'product_details',
            'condition_products',
            'is_raw_material',
            'suppliers',
            'status_array',
            'payment_status_array',
            'payment_type_array',
            'variation_id',
            'product_id',
            'po_nos',
            'taxes',
            'product_classes',
            'payment_types',
            'payment_status_array',
            'categories',
            'sub_categories',
            'brands',
            'units',
            'colors',
            'sizes',
            'grades',
            'taxes_array',
            'customer_types',
            'exchange_rate_currencies',
            'discount_customer_types',
            'users',
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
            ['product_variation_id' => ['required','array', 'exits:variations,id']],
            ['product_variation_id.*' => ['required','integer', 'exits:variations,id']],
            ['product_condition_variation_id' => ['nullable','array']],
            ['product_condition_variation_id.*' => ['nullable','integer', 'exits:variations,id']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['customer_type_ids' => ['required', 'max:255']],
            ['discount_type' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );
//        dd($request->all());

        try {
            $product_variation_id['product_selected']=$request->product_variation_id;
            $product_condition_variation_id['product_selected']=$request->product_condition_variation_id;

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
            $data['product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($product_variation_id); //product ids to get the discount
            $data['condition_product_ids'] = $this->productUtil->extractProductVariationIdsfromProductTree($product_condition_variation_id); //product ids condition to get the discount
            $data['pct_data'] = $request->product_variation_id ?? [];
            $data['pci_data'] = $request->product_condition_variation_id ?? []; //product condition items
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
        if(!Empty($request->array)){

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

            return  '';


    }
    /**
     * get product details
     *
     * @param int $id
     * @return void
     */
    public function getProductConditionRows(Request $request)
    {
        $store_ids = $request->store_ids;
        $type = $request->type;
        $array = $request->array;
        $is_edit=$request->is_edit == '1'?1:0;

        if($request->product_array_old_ids){
            $array=array_diff($array,$request->product_array_old_ids);
        }
        $products = $this->productUtil->getProductDetailsUsingArrayIds($array, $store_ids,true);
        return view('sales_promotion.partials.product_conditions_row')->with(compact(
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
