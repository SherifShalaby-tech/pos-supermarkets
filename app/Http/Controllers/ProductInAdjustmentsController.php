<?php

namespace App\Http\Controllers;

use App\Models\AddStockLine;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\ProductInAdjustment;
use App\Models\ProductInAdjustmentDetails;
use App\Models\ProductStore;
use App\Models\Size;
use App\Models\StorePos;
use App\Models\Transaction;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProductInAdjustmentsController extends Controller
{
        /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $productUtil;
    protected $transactionUtil;

    /**
     * Constructor
     *
     * @param transactionUtil $transactionUtil
     * @param Util $commonUtil
     * @param ProductUtil $productUtil
     * @return void
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil, TransactionUtil $transactionUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
    }
    public function index(){
        $product_adjustment = ProductInAdjustment::get();
        return view('product_in_adjustment.index')->with(compact(
            'product_adjustment'
        ));
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (request()->ajax()) {
            $products = Product::leftjoin('variations', function ($join) {
                $join->on('products.id', 'variations.product_id')->whereNull('variations.deleted_at');
            })
                ->leftjoin('add_stock_lines', function ($join) {
                    $join->on('variations.id', 'add_stock_lines.variation_id')->where('add_stock_lines.expiry_date', '>=', date('Y-m-d'));
                })
                ->leftjoin('colors', 'variations.color_id', 'colors.id')
                ->leftjoin('sizes', 'variations.size_id', 'sizes.id')
                ->leftjoin('grades', 'variations.grade_id', 'grades.id')
                ->leftjoin('units', 'variations.unit_id', 'units.id')
                ->leftjoin('product_classes', 'products.product_class_id', 'product_classes.id')
                ->leftjoin('categories', 'products.category_id', 'categories.id')
                ->leftjoin('categories as sub_categories', 'products.sub_category_id', 'sub_categories.id')
                ->leftjoin('brands', 'products.brand_id', 'brands.id')
                ->leftjoin('supplier_products', 'products.id', 'supplier_products.product_id')
                ->leftjoin('users', 'products.created_by', 'users.id')
                ->leftjoin('users as edited', 'products.edited_by', 'users.id')
                ->leftjoin('taxes', 'products.tax_id', 'taxes.id')
                ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id');
                $store_id = $this->transactionUtil->getFilterOptionValues($request)['store_id'];

                $store_query = '';
                if (!empty($store_id)) {
                    // $products->where('product_stores.store_id', $store_id);
                    $store_query = 'AND store_id=' . $store_id;
                }
            $products = $products->select(
                'products.*',
                'add_stock_lines.batch_number',
                'variations.sub_sku',
                'product_classes.name as product_class',
                'categories.name as category',
                'sub_categories.name as sub_category',
                'brands.name as brand',
                'colors.name as color',
                'sizes.name as size',
                'grades.name as grade',
                'units.name as unit',
                'taxes.name as tax',
                'variations.id as variation_id',
                'variations.name as variation_name',
                'variations.default_purchase_price',
                'variations.default_sell_price as default_sell_price',
                'add_stock_lines.expiry_date as exp_date',
                'users.name as created_by_name',
                'edited.name as edited_by_name',
                DB::raw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN variations as v ON product_stores.variation_id=v.id WHERE v.id=variations.id ' . $store_query . ') as current_stock'),
            )->with(['supplier'])
                ->groupBy('variations.id');
            return DataTables::of($products)
                ->addColumn('image', function ($row) {
                    $image = $row->getFirstMediaUrl('product');
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/uploads/' . session('logo')) . '" height="50px" width="50px">';
                    }
                })
                ->editColumn('variation_name', '@if($variation_name != "Default"){{$variation_name}} @else {{$name}}
                @endif')
                ->editColumn('sub_sku', '{{$sub_sku}}')
                ->addColumn('product_class', '{{$product_class}}')
                ->addColumn('category', '{{$category}}')
                ->addColumn('sub_category', '{{$sub_category}}')
                ->addColumn('purchase_history', function ($row) {
                    $html = '<a data-href="' . action('ProductController@getPurchaseHistory', $row->id) . '"
                    data-container=".view_modal" class="btn btn-modal">' . __('lang.view') . '</a>';
                    return $html;
                })
                ->editColumn('supplier_name', function ($row) {
                    return $row->supplier->name ?? '';
                })
                ->editColumn('batch_number', '{{$batch_number}}')
                ->editColumn('default_sell_price', function ($row) {
                    $price= AddStockLine::where('variation_id',$row->variation_id)
                        ->whereColumn('quantity',">",'quantity_sold')->first();
                    $price= $price? ($price->sell_price > 0 ? $price->sell_price : $row->default_sell_price):$row->default_sell_price;
                    return $this->productUtil->num_f($price);
                })//, '{{@num_format($default_sell_price)}}')
                ->editColumn('default_purchase_price', function ($row) {
                    $price= AddStockLine::where('variation_id',$row->variation_id)
                        ->whereColumn('quantity',">",'quantity_sold')->first();
                    $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->default_purchase_price):$row->default_purchase_price;

                    return $this->productUtil->num_f($price);
                })//, '{{@num_format($default_purchase_price)}}')
                ->addColumn('tax', '{{$tax}}')
                ->editColumn('brand', '{{$brand}}')
                ->editColumn('unit', '{{$unit}}')
                ->editColumn('color', function ($row){
                    $color='';
                    if($row->variation_name == "Default"){
                        if(isset($row->multiple_colors)){
                          $color_m=Color::whereId($row->multiple_colors)->first();
                          if($color_m){
                             $color= $color_m ->name;
                          }
                        }
                    }else{
                        $color = $row->color;
                    }
                    return $color;
                })
                ->editColumn('size', function ($row){
                    $size='';
                    if($row->variation_name == "Default"){

                        if(isset($row->multiple_sizes)){
                            $size_m=Size::whereId($row->multiple_sizes)->first();
                            if($size_m){
                                $size= $size_m ->name;
                            }
                        }

                    }else{
                        $size = $row->size;
                    }
                    return $size;
                })
                ->editColumn('grade', '{{$grade}}')
                ->editColumn('current_stock', '@if($is_service){{@num_format(0)}} @else{{@num_format($current_stock)}}@endif')
                ->addColumn('current_stock_value', function ($row) {
                    return $this->productUtil->num_f($row->current_stock * $row->default_purchase_price);
                })
                ->addColumn('customer_type', function ($row) {
                    return $row->customer_type;
                })
                ->editColumn('exp_date', '@if(!empty($exp_date)){{@format_date($exp_date)}}@endif')
                ->addColumn('manufacturing_date', '@if(!empty($manufacturing_date)){{@format_date($manufacturing_date)}}@endif')
                ->editColumn('discount',function ($row) {
                    $discount_text=$row->discount?$row->discount.' - ':'';
                    $discounts= ProductDiscount::where('product_id',$row->id)->get();
                    foreach ($discounts as $k=>$discount){
                        if($k != 0){
                            $discount_text.=' - ';
                        }
                        $discount_text.= $discount->discount;
                    }
                    return $discount_text;
                    //'{{@num_format($discount)}}'
                })
                ->editColumn('active', function ($row) {
                    if ($row->active) {
                        return __('lang.yes');
                    } else {
                        return __('lang.no');
                    }
                })
                ->editColumn('created_by', '{{$created_by_name}}')
                ->rawColumns([
                    'selection_checkbox',
                    'selection_checkbox_send',
                    'selection_checkbox_delete',
                    'image',
                    'variation_name',
                    'sku',
                    'product_class',
                    'category',
                    'sub_category',
                    'purchase_history',
                    'batch_number',
                    'sell_price',
                    'tax',
                    'brand',
                    'unit',
                    'color',
                    'size',
                    'grade',
                    'is_service',
                    'customer_type',
                    'expiry',
                    'manufacturing_date',
                    'discount',
                    'purchase_price',
                    'created_by',
                    'action',
                ])
                ->make(true);
        }


        return view('product_in_adjustment.create');
    }
    public function store(Request $request){
        // return Auth::user()->id;
        // return $request;
        // foreach ($request->selected_data as $data){
        //     return $data['id'];
        // }
        $user_id =Auth::user()->id;
        $store_pos = StorePos::where('user_id', $user_id)->first();
        if($request->total_shortage_value > 0 ){
            $ProductInAdjustment = ProductInAdjustment::create([
                'total_shortage_value'=>$request->total_shortage_value,
                'created_by'=> $user_id,
                'store_id'=> !empty($store_pos) ? $store_pos->store_id : null,
            ]);
            Transaction::create([
                'grand_total' => $this->commonUtil->num_uf($request->total_shortage_value),
                'final_total' => $this->commonUtil->num_uf($request->total_shortage_value),
                'store_id' => 1,
                'type' => 'expense',
                'status' => 'final',
                'invoice_no' => $this->productUtil->getNumberByType('expense'),
                'transaction_date' => $ProductInAdjustment->created_at,
                'expense_category_id' => 1,
                'expense_beneficiary_id' => 1,
                'source_id' => 1,
                'source_type' => 'store',
                'created_by' => $user_id,
            ]);
            foreach ($request->selected_data as $data){
                ProductStore::where('product_id', $data['id'])->where('variation_id',$data['variation_id'])
                ->where('store_id',$store_pos->store_id)
                ->update([
                    'qty_available' => $data['actual_stock'],
                ]);
                ProductInAdjustmentDetails::create([
                    'product_id'=>$data['id'],
                    'variation_id'=>$data['variation_id'],
                    'product_adjustments_id'=>$ProductInAdjustment->id,
                    'old_stock'=>$data['current_stock'],
                    'new_stock'=>$data['actual_stock'],
                    'shortage'=>$data['shortage'],
                    'shortage_value'=>$data['shortage_value'],
                ]);

            }

        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
           $product_adjust = ProductInAdjustment::find($id);
            $products = ProductInAdjustmentDetails::where('product_adjustments_id',$id)->get();
            foreach($products as $product){
                ProductStore::where('product_id', $product->product_id)->where('variation_id',$product->variation_id)
                ->where('store_id',$product_adjust->store_id)
                ->update([
                    'qty_available' => $product->old_stock,
                ]);
                $product->delete();
            }
            $product_adjust->delete();
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
}
