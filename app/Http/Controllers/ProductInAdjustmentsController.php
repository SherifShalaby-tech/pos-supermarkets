<?php

namespace App\Http\Controllers;

use App\Models\AddStockLine;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\ExpenseBeneficiary;
use App\Models\ExpenseCategory;
use App\Models\Grade;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductDiscount;
use App\Models\ProductInAdjustment;
use App\Models\ProductInAdjustmentDetails;
use App\Models\ProductStore;
use App\Models\Size;
use App\Models\Store;
use App\Models\StorePos;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
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
        $product_adjustment = ProductInAdjustment::orderBy('created_at', 'desc')->get();
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
        $process_type = $request->process_type??null;
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

            if (!empty(request()->product_id)) {
                $products->where('products.id', request()->product_id);
            }

            if (!empty(request()->product_class_id)) {
                $products->where('products.product_class_id', request()->product_class_id);
            }

            if (!empty(request()->category_id)) {
                $products->where('products.category_id', request()->category_id);
            }

            if (!empty(request()->sub_category_id)) {
                $products->where('products.sub_category_id', request()->sub_category_id);
            }

            if (!empty(request()->tax_id)) {
                $products->where('tax_id', request()->tax_id);
            }

            if (!empty(request()->brand_id)) {
                $products->where('products.brand_id', request()->brand_id);
            }

            if (!empty(request()->supplier_id)) {
                $products->where('supplier_products.supplier_id', request()->supplier_id);
            }

            if (!empty(request()->unit_id)) {
                $products->where('variations.unit_id', request()->unit_id);
            }

            if (!empty(request()->color_id)) {
                $products->where('variations.color_id', request()->color_id);
            }

            if (!empty(request()->size_id)) {
                $products->where('variations.size_id', request()->size_id);
            }

            if (!empty(request()->grade_id)) {
                $products->where('variations.grade_id', request()->grade_id);
            }

            if (!empty(request()->customer_type_id)) {
                $products->whereJsonContains('show_to_customer_types', request()->customer_type_id);
            }

            if (!empty(request()->created_by)) {
                $products->where('products.created_by', request()->created_by);
            }
            if (request()->active == '1' || request()->active == '0') {
                $products->where('products.active', request()->active);
            }
            if (request()->show_zero_stocks == '0') {
                $products->where('is_service', 0)->havingRaw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN variations as v ON product_stores.variation_id=v.id WHERE v.id=variations.id ' . $store_query . ') > ?', [0]);
            }
            if (!empty(request()->is_raw_material)) {
                $products->where('is_raw_material', 1);
            } else {
                $products->where('is_raw_material', 0);
            }
            $is_add_stock = request()->is_add_stock;
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
                DB::raw('(SELECT AVG(add_stock_lines.purchase_price) FROM add_stock_lines JOIN variations as v ON add_stock_lines.variation_id=v.id WHERE v.id=variations.id ' . $store_query . ') as avg_purchase_price'),
            )->with(['supplier'])
                ->groupBy('variations.id');
            
            
            // return $products;
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
                ->editColumn('is_service',function ($row) {
                    return $row->is_service=='1'?'<span class="badge badge-danger">'.Lang::get('lang.is_have_service').'</span>':'';
                })
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
                    ->whereHas('transaction', function ($query) {
                        $query->where('type', '!=', 'supplier_service');
                    })
                        ->latest()->first();
                    $price= $price? ($price->sell_price > 0 ? $price->sell_price : $row->default_sell_price):$row->default_sell_price;
                    return $this->productUtil->num_f($price);
                })//, '{{@num_format($default_sell_price)}}')
                ->editColumn('default_purchase_price', function ($row) {
                    $price= AddStockLine::where('variation_id',$row->variation_id)
                    ->whereHas('transaction', function ($query) {
                        $query->where('type', '!=', 'supplier_service');
                    })
                    ->latest()->first();
                    $price= $price? ($price->purchase_price):$row->default_purchase_price;

                    return $this->productUtil->num_f($price);
                })
                ->editColumn('avg_purchase_price', '{{@num_format($avg_purchase_price)}}')
                //, '{{@num_format($default_purchase_price)}}')
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
                // ->editColumn('current_stock', '@if($is_service){{@num_format(0)}} @else{{@num_f($current_stock)}}@endif')
                ->editColumn('current_stock', function ($row) {
                    if($row->is_service){
                        return $this->productUtil->num_f(0);
                    }else{
                        return $this->productUtil->num_f($row->current_stock);
                    }
                    
                })
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
                ->addColumn('supplier', function ($row) {
                    $query = Transaction::leftjoin('add_stock_lines', 'transactions.id', '=', 'add_stock_lines.transaction_id')
                        ->leftjoin('suppliers', 'transactions.supplier_id', '=', 'suppliers.id')
                        ->where('transactions.type', 'add_stock')
                        ->where('add_stock_lines.product_id', $row->id)
                        ->select('suppliers.name')
                        ->orderBy('transactions.id', 'desc')
                        ->first();
                    return $query->name ?? '';
                })


                ->addColumn('selection_checkbox', function ($row) use ($is_add_stock) {
                    if ($is_add_stock == 1 && $row->is_service == 1) {
                        $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';

                    } else {
                        if ($row->current_stock >= 0 ) {
                            $html = '<input type="checkbox" name="product_selected" class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';
                        } else {
                            $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';
                        }
                    }
                    return $html;
                })->addColumn('selection_checkbox_send', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_send" class="product_selected_send" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';

                    return $html;
                })
                ->addColumn('selection_checkbox_delete', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_delete" class="product_selected_delete" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';


                    return $html;
                })



                ->addColumn(
                    'action',
                    function ($row) {
                        if($row->parent_branch_id != null ){
                            return '';
                        }
                        $html =
                            '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') .
                            '<span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('product_module.product.view')) {
                            $html .=
                                '<li><a data-href="' . action('ProductController@show', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('product_module.product.create_and_edit')) {
                            $html .=
                                '<li><a href="' . action('ProductController@edit', $row->id) . '" class="btn"
                            target="_blank"><i class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('stock.add_stock.create_and_edit')) {
                            $html .=
                                '<li><a target="_blank" href="' . action('AddStockController@create', ['variation_id' => $row->variation_id, 'product_id' => $row->id]) . '" class="btn"
                            target="_blank"><i class="fa fa-plus"></i> ' . __('lang.add_new_stock') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        // if (auth()->user()->can('product_module.product.delete')) {

                        //     $html .=
                        //         '<li>
                        //     <a data-href="' . action('ProductController@destroy', $row->variation_id) . '"
                        //         data-check_password="' . action('UserController@checkPassword', Auth::user()->id) . '"
                        //         class="btn text-red delete_product"><i class="fa fa-trash"></i>
                        //         ' . __('lang.delete') . '</a>
                        // </li>';
                        // }

                        $html .= '</ul></div>';

                        return $html;
                    }
                )

                ->setRowAttr([
                    'data-href' => function ($row) {
                        if (auth()->user()->can("product.view")) {
                            return  action('ProductController@show', [$row->id]);
                        } else {
                            return '';
                        }
                    }
                ])
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
        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $suppliers = Supplier::pluck('name', 'id');

        $stores  = Store::getDropdown();
        $users = User::Notview()->pluck('name', 'id');


        return view('product_in_adjustment.create')->with(compact(
            'product_classes',
            'categories',
            'sub_categories',
            'brands',
            'units',
            'colors',
            'sizes',
            'grades',
            'taxes',
            'customers',
            'customer_types',
            'discount_customer_types',
            'users',
            'stores',
            'suppliers'
        ));
    }
    public function store(Request $request){
        // return $request;
        // return Auth::user()->id;
        // return $request;
        // foreach ($request->selected_data as $data){
        //     return $data['id'];
        // }
        try {
            $user_id =Auth::user()->id;
            $store_pos = StorePos::where('user_id', $user_id)->first();
       
            $ProductInAdjustment = ProductInAdjustment::create([
                'total_shortage_value'=>$request->total_shortage_value ?? null,
                'created_by'=> $user_id,
                'store_id'=> !empty($store_pos) ? $store_pos->store_id : null,
            ]);
            if($request->total_shortage_value || $request->expenses_total_shortage_value){
              
                $expenses_category = ExpenseCategory::where('name','Adjustment')->orWhere('name','adjustment')->first();
                if(!$expenses_category){
                    $expenses_category = ExpenseCategory::create([
                        'name' => 'Adjustment',
                        'created_by' => 1
                    ]);
                }
                $expenses_beneficiary = ExpenseBeneficiary::where('name','الجرد')->first();
                if(!$expenses_beneficiary){
                    $expenses_beneficiary = ExpenseBeneficiary::create([
                        'name' => 'الجرد',
                        'expense_category_id' => $expenses_category->id,
                        'created_by' => 1,
                    ]);
                }
          
                Transaction::create([
                    'grand_total' => $this->commonUtil->num_uf($request->expenses_total_shortage_value),
                    'final_total' => $this->commonUtil->num_uf($request->expenses_total_shortage_value),
                    'store_id' => $store_pos->store_id,
                    'type' => 'expense',
                    'status' => 'final',
                    'invoice_no' => $this->productUtil->getNumberByType('expense'),
                    'transaction_date' => $ProductInAdjustment->created_at,
                    'expense_category_id' => $expenses_category->id,
                    'expense_beneficiary_id' => $expenses_beneficiary->id,
                    'source_id' => 1,
                    'source_type' => 'store',
                    'created_by' => $user_id,
                ]);
    
            }
                foreach ($request->selected_data as $data){
                    if($request->total_shortage_value  ){
                        if(isset($data['actual_stock'])){
                            ProductStore::where('product_id', $data['id'])->where('variation_id',$data['variation_id'])
                            ->where('store_id',$store_pos->store_id)
                            ->update([
                                'qty_available' => $data['actual_stock'],
                            ]);
                        }
                    }      
                    if(isset($data['default_purchase_price']) || isset($data['default_sell_price'])){
                        if($data['default_purchase_price'] != 0 && $data['default_sell_price'] != 0){
                            $stocks = AddStockLine::where('product_id', $data['id'])->where('variation_id',$data['variation_id'])->get();
                            foreach($stocks as $stock){
                                if(isset($data['default_purchase_price'])){
                                    $stock->update([
                                        'purchase_price' => $data['default_purchase_price'],
                                    ]);
                                }
                                if(isset($data['default_sell_price'])){
                                    $stock->update([
                                        'sell_price' => $data['default_sell_price'],
                                    ]);
                                }     
                           }
                        }
                       
                        
                    }
                    ProductInAdjustmentDetails::create([
                        'product_id'=>$data['id'],
                        'variation_id'=>$data['variation_id'],
                        'product_adjustments_id'=>$ProductInAdjustment->id,
                        'old_stock'=>$data['current_stock'] ?? null,
                        'new_stock'=>$data['actual_stock'] ?? null,
                        'shortage'=>$data['shortage']??null,
                        'shortage_value'=>$data['shortage_value']?? null,
                        'old_purchase_price' =>$data['old_purchase_price']?? null,
                        'new_purchase_price' =>$data['default_purchase_price']?? null,
                        'old_sell_price' =>$data['old_sell_price']?? null,
                        'new_sell_price' =>$data['default_sell_price']?? null,
                    ]);
                }
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
           return $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
          

       
    }
    public function getDetails($id){
         $adjustment_details = ProductInAdjustmentDetails::where('product_adjustments_id',$id)->with('product')->get();
        return view('product_in_adjustment.details')->with(compact('adjustment_details'));
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
           $product_adjust->deleted_by= request()->user()->id;
           $product_adjust->save();
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
