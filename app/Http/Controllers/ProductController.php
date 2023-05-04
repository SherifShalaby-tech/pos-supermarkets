<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\AddStockLine;
use App\Models\Brand;
use App\Models\Employee;
use App\Models\Printer;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Grade;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductDiscount;
use App\Models\ProductStore;
use App\Models\Size;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Models\Variation;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Lang;
class ProductController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductStocks(Request $request)
    {
        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::where('is_raw_material_unit', 0)->orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $stores  = Store::getDropdown();
        $users  = User::Notview()->orderBy('name', 'asc')->pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');
        $page = 'product_stock';

        return view('product.index')->with(compact(
            'users',
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
            'stores',
            'suppliers',
            'page'
        ));
    }
//     public function showPr(Request $request)
//     {
//         $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
//         $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
//         $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
//         $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
//         $units = Unit::where('is_raw_material_unit', 0)->orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
//         $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
//         $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
//         $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
//         $taxes = Tax::orderBy('name', 'asc')->pluck('name', 'id');
//         $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
//         $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
//         $discount_customer_types = Customer::getCustomerTreeArray();
//         $stores  = Store::getDropdown();
//         $users  = User::Notview()->orderBy('name', 'asc')->pluck('name', 'id');
//         $suppliers = Supplier::pluck('name', 'id');
//         $page = 'product_stock';

//         return view('product.show-pr')->with(compact(
//             'users',
//             'product_classes',
//             'categories',
//             'sub_categories',
//             'brands',
//             'units',
//             'colors',
//             'sizes',
//             'grades',
//             'taxes',
//             'customers',
//             'customer_types',
//             'discount_customer_types',
//             'stores',
//             'suppliers',
//             'page'
//         ));
//     }
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
    public function index(Request $request)
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
                        if (auth()->user()->can('product_module.product.delete')) {

                            $html .=
                                '<li>
                            <a data-href="' . action('ProductController@destroy', $row->variation_id) . '"
                                data-check_password="' . action('UserController@checkPassword', Auth::user()->id) . '"
                                class="btn text-red delete_product"><i class="fa fa-trash"></i>
                                ' . __('lang.delete') . '</a>
                        </li>';
                        }

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

        return view('product.index')->with(compact(
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
//     public function showPrData(Request $request)
//     {
//         // SELECT * FROM products LEFT JOIN variations on products.id=variations.product_id where variations.unit_id IS NOT NULL;
//         // SELECT * FROM products LEFT JOIN variations on products.id=variations.product_id where variations.unit_id IS NULL;
        

// //         SELECT p.*,
// //         MIN(v.name)
// //    FROM products p
// //    JOIN variations v ON v.product_id = p.id
// // GROUP BY p.id, p.name

//         $process_type = $request->process_type??null;
//         if (request()->ajax()) {
//             $products = Product::leftjoin('variations', function ($join) {
//                 $join->on('products.id', 'variations.product_id')->whereNull('variations.deleted_at');
//             })
//                 ->leftjoin('add_stock_lines', function ($join) {
//                     $join->on('variations.id', 'add_stock_lines.variation_id')->where('add_stock_lines.expiry_date', '>=', date('Y-m-d'));
//                 })
//                 ->leftjoin('colors', 'variations.color_id', 'colors.id')
//                 ->leftjoin('sizes', 'variations.size_id', 'sizes.id')
//                 ->leftjoin('grades', 'variations.grade_id', 'grades.id')
//                 ->leftjoin('units', 'variations.unit_id', 'units.id')
//                 ->leftjoin('product_classes', 'products.product_class_id', 'product_classes.id')
//                 ->leftjoin('categories', 'products.category_id', 'categories.id')
//                 ->leftjoin('categories as sub_categories', 'products.sub_category_id', 'sub_categories.id')
//                 ->leftjoin('brands', 'products.brand_id', 'brands.id')
//                 ->leftjoin('supplier_products', 'products.id', 'supplier_products.product_id')
//                 ->leftjoin('users', 'products.created_by', 'users.id')
//                 ->leftjoin('users as edited', 'products.edited_by', 'users.id')
//                 ->leftjoin('taxes', 'products.tax_id', 'taxes.id')
//                 ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id');
//                 $products->whereNotNull('variations.unit_id');
               
//             $store_id = $this->transactionUtil->getFilterOptionValues($request)['store_id'];

//             $store_query = '';
//             if (!empty($store_id)) {
//                 $store_query = 'AND store_id=' . $store_id;
//             }

//             if (!empty(request()->product_id)) {
//                 $products->where('products.id', request()->product_id);
//             }

//             if (!empty(request()->product_class_id)) {
//                 $products->where('products.product_class_id', request()->product_class_id);
//             }

//             if (!empty(request()->category_id)) {
//                 $products->where('products.category_id', request()->category_id);
//             }

//             if (!empty(request()->sub_category_id)) {
//                 $products->where('products.sub_category_id', request()->sub_category_id);
//             }

//             if (!empty(request()->tax_id)) {
//                 $products->where('tax_id', request()->tax_id);
//             }

//             if (!empty(request()->brand_id)) {
//                 $products->where('products.brand_id', request()->brand_id);
//             }

//             if (!empty(request()->supplier_id)) {
//                 $products->where('supplier_products.supplier_id', request()->supplier_id);
//             }

//             if (!empty(request()->unit_id)) {
//                 $products->where('variations.unit_id', request()->unit_id);
//             }

//             if (!empty(request()->color_id)) {
//                 $products->where('variations.color_id', request()->color_id);
//             }

//             if (!empty(request()->size_id)) {
//                 $products->where('variations.size_id', request()->size_id);
//             }

//             if (!empty(request()->grade_id)) {
//                 $products->where('variations.grade_id', request()->grade_id);
//             }

//             if (!empty(request()->customer_type_id)) {
//                 $products->whereJsonContains('show_to_customer_types', request()->customer_type_id);
//             }

//             if (!empty(request()->created_by)) {
//                 $products->where('products.created_by', request()->created_by);
//             }
//             if (request()->active == '1' || request()->active == '0') {
//                 $products->where('products.active', request()->active);
//             }

//             if (!empty(request()->is_raw_material)) {
//                 $products->where('is_raw_material', 1);
//             } else {
//                 $products->where('is_raw_material', 0);
//             }
//             $is_add_stock = request()->is_add_stock;
//             $products = $products->select(
//                 'products.*',
//                 'add_stock_lines.batch_number',
//                 'add_stock_lines.id as stid',
//                 'variations.sub_sku',
//                 'variations.unit_id as unit_id',
//                 'product_classes.name as product_class',
//                 'categories.name as category',
//                 'sub_categories.name as sub_category',
//                 'brands.name as brand',
//                 'colors.name as color',
//                 'sizes.name as size',
//                 'grades.name as grade',
//                 'units.name as unit',
//                 'taxes.name as tax',
//                 'variations.id as variation_id',
//                 'variations.name as variation_name',
//                 'variations.default_purchase_price',
//                 'variations.default_sell_price as default_sell_price',
//                 'add_stock_lines.expiry_date as exp_date',
//                 'users.name as created_by_name',
//                 'edited.name as edited_by_name',
//                 DB::raw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN variations as v ON product_stores.variation_id=v.id WHERE v.id=variations.id ' . $store_query . ') as current_stock'),
//             )->with(['supplier'])
//                 ->groupBy('variations.id');

//             // return $products;
//             // $newProduct=[];
//             // foreach($products as $product){
//             //     if($product->unit_id){
//             //         return 55;
//             //     }

//             // }
//             return DataTables::of($products)
//                 ->addColumn('image', function ($row) {
//                     $image = $row->getFirstMediaUrl('product');
//                     if (!empty($image)) {
//                         return '<img src="' . $image . '" height="50px" width="50px">';
//                     } else {
//                         return '<img src="' . asset('/uploads/' . session('logo')) . '" height="50px" width="50px">';
//                     }
//                 })
//                 ->editColumn('product_name', '{{$name}}')
//                 ->editColumn('variation_name', '@if($variation_name != "Default"){{$variation_name}} @else {{$name}}
//                 @endif')
//                 ->editColumn('sub_sku', '{{$sub_sku}}')
//                 ->editColumn('is_service',function ($row) {
//                     return $row->is_service=='1'?'<span class="badge badge-danger">'.Lang::get('lang.is_have_service').'</span>':'';
//                 })
//                 ->addColumn('product_class', '{{$product_class}}')
//                 ->addColumn('category', '{{$category}}')
//                 ->addColumn('sub_category', '{{$sub_category}}')
//                 ->addColumn('purchase_history', function ($row) {
//                     $html = '<a data-href="' . action('ProductController@getPurchaseHistory', $row->id) . '"
//                     data-container=".view_modal" class="btn btn-modal">' . __('lang.view') . '</a>';
//                     return $html;
//                 })
//                 ->editColumn('supplier_name', function ($row) {
//                     return $row->supplier->name ?? '';
//                 })
//                 ->editColumn('batch_number', '{{$batch_number}}')
//                 ->editColumn('default_sell_price', function ($row) {
//                     $price= AddStockLine::where('variation_id',$row->variation_id)
//                         ->whereColumn('quantity',">",'quantity_sold')->first();
//                     $price= $price? ($price->sell_price > 0 ? $price->sell_price : $row->default_sell_price):$row->default_sell_price;
//                     return $this->productUtil->num_f($price);
//                 })//, '{{@num_format($default_sell_price)}}')
//                 ->editColumn('default_purchase_price', function ($row) {
//                     $price= AddStockLine::where('variation_id',$row->variation_id)
//                         ->whereColumn('quantity',">",'quantity_sold')->first();
//                     $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->default_purchase_price):$row->default_purchase_price;

//                     return $this->productUtil->num_f($price);
//                 })//, '{{@num_format($default_purchase_price)}}')
//                 ->addColumn('tax', '{{$tax}}')
//                 ->editColumn('brand', '{{$brand}}')
//                 ->editColumn('unit', '{{$unit}}')
//                 ->editColumn('color', function ($row){
//                     $color='';
//                     if($row->variation_name == "Default"){
//                         if(isset($row->multiple_colors)){
//                           $color_m=Color::whereId($row->multiple_colors)->first();
//                           if($color_m){
//                              $color= $color_m ->name;
//                           }
//                         }
//                     }else{
//                         $color = $row->color;
//                     }
//                     return $color;
//                 })
//                 ->editColumn('size', function ($row){
//                     $size='';
//                     if($row->variation_name == "Default"){

//                         if(isset($row->multiple_sizes)){
//                             $size_m=Size::whereId($row->multiple_sizes)->first();
//                             if($size_m){
//                                 $size= $size_m ->name;
//                             }
//                         }

//                     }else{
//                         $size = $row->size;
//                     }
//                     return $size;
//                 })
//                 ->editColumn('grade', '{{$grade}}')
//                 ->editColumn('current_stock', '@if($is_service){{@num_format(0)}} @else{{@num_format($current_stock)}}@endif')
//                 ->addColumn('current_stock_value', function ($row) {
//                     return $this->productUtil->num_f($row->current_stock * $row->default_purchase_price);
//                 })
//                 ->addColumn('customer_type', function ($row) {
//                     return $row->customer_type;
//                 })
//                 ->editColumn('exp_date', '@if(!empty($exp_date)){{@format_date($exp_date)}}@endif')
//                 ->addColumn('manufacturing_date', '@if(!empty($manufacturing_date)){{@format_date($manufacturing_date)}}@endif')
//                 ->editColumn('discount',function ($row) {
//                     $discount_text=$row->discount?$row->discount.' - ':'';
//                     $discounts= ProductDiscount::where('product_id',$row->id)->get();
//                     foreach ($discounts as $k=>$discount){
//                         if($k != 0){
//                             $discount_text.=' - ';
//                         }
//                         $discount_text.= $discount->discount;
//                     }
//                     return $discount_text;
//                     //'{{@num_format($discount)}}'
//                 })
//                 ->editColumn('active', function ($row) {
//                     if ($row->active) {
//                         return __('lang.yes');
//                     } else {
//                         return __('lang.no');
//                     }
//                 })
//                 ->editColumn('created_by', '{{$created_by_name}}')
//                 ->addColumn('supplier', function ($row) {
//                     $query = Transaction::leftjoin('add_stock_lines', 'transactions.id', '=', 'add_stock_lines.transaction_id')
//                         ->leftjoin('suppliers', 'transactions.supplier_id', '=', 'suppliers.id')
//                         ->where('transactions.type', 'add_stock')
//                         ->where('add_stock_lines.product_id', $row->id)
//                         ->select('suppliers.name')
//                         ->orderBy('transactions.id', 'desc')
//                         ->first();
//                     return $query->name ?? '';
//                 })


//                 ->addColumn('selection_checkbox', function ($row) use ($is_add_stock) {
//                     if ($is_add_stock == 1 && $row->is_service == 1) {
//                         $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';

//                     } else {
//                         if ($row->current_stock >= 0 ) {
//                             $html = '<input type="checkbox" name="product_selected" class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';
//                         } else {
//                             $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';
//                         }
//                     }
//                     return $html;
//                 })->addColumn('selection_checkbox_send', function ($row)  {
//                     $html = '<input type="checkbox" name="product_selected_send" class="product_selected_send" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';

//                     return $html;
//                 })
//                 ->addColumn('selection_checkbox_delete', function ($row)  {
//                     $html = '<input type="checkbox" name="product_selected_delete" class="product_selected_delete" value="' . $row->variation_id . '" data-product_id="' . $row->id . '" />';


//                     return $html;
//                 })





//                 ->addColumn(
//                     'action',
//                     function ($row) {
//                         if($row->parent_branch_id != null ){
//                             return '';
//                         }
//                         $html =
//                             '<div class="btn-group">
//                             <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
//                                 aria-haspopup="true" aria-expanded="false">' . __('lang.action') .
//                             '<span class="caret"></span>
//                                 <span class="sr-only">Toggle Dropdown</span>
//                             </button>
//                             <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

//                         if (auth()->user()->can('product_module.product.view')) {
//                             $html .=
//                                 '<li><a data-href="' . action('ProductController@show', $row->id) . '"
//                                 data-container=".view_modal" class="btn btn-modal"><i class="fa fa-eye"></i>
//                                 ' . __('lang.view') . '</a></li>';
//                         }
//                         $html .= '<li class="divider"></li>';
//                         if (auth()->user()->can('product_module.product.create_and_edit')) {
//                             $html .=
//                                 '<li><a href="' . action('ProductController@edit', $row->id) . '" class="btn"
//                             target="_blank"><i class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a></li>';
//                         }
//                         $html .= '<li class="divider"></li>';
//                         if (auth()->user()->can('stock.add_stock.create_and_edit')) {
//                             $html .=
//                                 '<li><a target="_blank" href="' . action('AddStockController@create', ['variation_id' => $row->variation_id, 'product_id' => $row->id]) . '" class="btn"
//                             target="_blank"><i class="fa fa-plus"></i> ' . __('lang.add_new_stock') . '</a></li>';
//                         }
//                         $html .= '<li class="divider"></li>';
//                         if (auth()->user()->can('product_module.product.delete')) {

//                             $html .=
//                                 '<li>
//                             <a data-href="' . action('ProductController@destroy', $row->variation_id) . '"
//                                 data-check_password="' . action('UserController@checkPassword', Auth::user()->id) . '"
//                                 class="btn text-red delete_product"><i class="fa fa-trash"></i>
//                                 ' . __('lang.delete') . '</a>
//                         </li>';
//                         }

//                         $html .= '</ul></div>';

//                         return $html;
//                     }
//                 )

//                 ->setRowAttr([
//                     'data-href' => function ($row) {
//                         if (auth()->user()->can("product.view")) {
//                             return  action('ProductController@show', [$row->id]);
//                         } else {
//                             return '';
//                         }
//                     }
//                 ])
//                 ->rawColumns([
//                     'selection_checkbox',
//                     'selection_checkbox_send',
//                     'selection_checkbox_delete',
//                     'image',
//                     'product_name',
//                     'variation_name',
//                     'sku',
//                     'product_class',
//                     'category',
//                     'sub_category',
//                     'purchase_history',
//                     'batch_number',
//                     'sell_price',
//                     'tax',
//                     'brand',
//                     'unit',
//                     'color',
//                     'size',
//                     'grade',
//                     'is_service',
//                     'customer_type',
//                     'expiry',
//                     'manufacturing_date',
//                     'discount',
//                     'purchase_price',
//                     'created_by',
//                     'action',
//                 ])
//                 ->make(true);
//         }
//         $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
//         $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
//         $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
//         $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
//         $units = Unit::orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
//         $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
//         $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
//         $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
//         $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
//         $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
//         $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
//         $discount_customer_types = Customer::getCustomerTreeArray();
//         $suppliers = Supplier::pluck('name', 'id');

//         $stores  = Store::getDropdown();
//         $users = User::Notview()->pluck('name', 'id');

//         return view('product.show-pr')->with(compact(
//             'product_classes',
//             'categories',
//             'sub_categories',
//             'brands',
//             'units',
//             'colors',
//             'sizes',
//             'grades',
//             'taxes',
//             'customers',
//             'customer_types',
//             'discount_customer_types',
//             'users',
//             'stores',
//             'suppliers'
//         ));
//     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('product_module.product.create_and_edit')) {
            abort(403, 'Unauthorized action.');
        }

        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::where('is_raw_material_unit', 0)->orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $users = User::Notview()->orderBy('name', 'asc')->pluck('name', 'id');
        $stores  = Store::all();
        $stores_select  = Store::getDropdown();
        $quick_add = request()->quick_add;
        $raw_materials  = Product::where('is_raw_material', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $raw_material_units  = Unit::orderBy('name', 'asc')->pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');
        $printers = Printer::get(['id','name']);

        if ($quick_add) {
            return view('product.create_quick_add')->with(compact(
                'quick_add',
                'suppliers',
                'raw_materials',
                'raw_material_units',
                'product_classes',
                'categories',
                'sub_categories',
                'brands',
                'units',
                'colors',
                'sizes',
                'grades',
                'stores_select',
                'taxes',
                'customers',
                'customer_types',
                'discount_customer_types',
                'stores',
                'printers'
            ));
        }

        return view('product.create')->with(compact(
            'suppliers',
            'raw_materials',
            'raw_material_units',
            'product_classes',
            'categories',
            'sub_categories',
            'brands',
            'units',
            'colors',
            'sizes',
            'grades',
            'taxes',
            'stores_select',
            'customers',
            'customer_types',
            'discount_customer_types',
            'stores',
            'printers'
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

        if (!auth()->user()->can('product_module.product.create_and_edit')) {
            abort(403, 'Unauthorized action.');
        }
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['store_ids' => ['required']],
//            ['purchase_price' => ['required', 'max:25', 'decimal']],
//            ['sell_price' => ['required', 'max:25', 'decimal']],
        );
//        try {



            $product_data = [
                'name' => $request->name,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'product_class_id' => $request->product_class_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'sku' => !empty($request->sku) ? $request->sku : $this->productUtil->generateSku($request->name),
                'multiple_units' => $request->multiple_units,
                'multiple_colors' => $request->multiple_colors,
                'multiple_sizes' => $request->multiple_sizes,
                'multiple_grades' => $request->multiple_grades,
                'is_service' => !empty($request->is_service) ? 1 : 0,
                'product_details' => $request->product_details,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'other_cost' => !empty($request->other_cost) ? $this->commonUtil->num_uf($request->other_cost) : 0,
                'purchase_price' => !empty($request->is_service) ? $this->commonUtil->num_uf($request->purchase_price) : 0,
                'sell_price' => !empty($request->is_service) ? $this->commonUtil->num_uf($request->sell_price):0 ,
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'show_to_customer' => !empty($request->show_to_customer) ? 1 : 0,
                'show_to_customer_types' => $request->show_to_customer_types,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'this_product_have_variant' => !empty($request->this_product_have_variant) ? 1 : 0,
                'price_based_on_raw_material' => !empty($request->price_based_on_raw_material) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' => !empty($request->buy_from_supplier) ? 1 : 0,
                'type' => !empty($request->this_product_have_variant) ? 'variable' : 'single',
                'active' => !empty($request->active) ? 1 : 0,
                'have_weight' => !empty($request->have_weight) ? 1 : 0,
                'created_by' => Auth::user()->id
            ];


            DB::beginTransaction();

            $product = Product::create($product_data);
            $index_discounts=[];
            if($request->has('discount_type')){
                if(count($request->discount_type)>0){
                    $index_discounts=array_keys($request->discount_type);
                }
            }


                foreach ($index_discounts as $index_discount){
                    $discount_customers = $this->getDiscountCustomerFromType($request->get('discount_customer_types_'.$index_discount));
                    $data_des=[
                        'product_id' => $product->id,
                        'discount_type' => $request->discount_type[$index_discount],
                        'discount_category' => $request->discount_category[$index_discount],
                        'is_discount_permenant'=>!empty($request->is_discount_permenant[$index_discount])? 1 : 0,
                        'discount_customer_types' => $request->get('discount_customer_types_'.$index_discount),
                        'discount_customers' => $discount_customers,
                        'discount' => $this->commonUtil->num_uf($request->discount[$index_discount]),
                        'discount_start_date' => !empty($request->discount_start_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_start_date[$index_discount]) : null,
                        'discount_end_date' => !empty($request->discount_end_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_end_date[$index_discount]) : null
                    ];

                    ProductDiscount::create($data_des);
                }


            if($request->printers){
                // loop printers
                foreach ($request->printers as $printer){
                    $data = [
                        'printer_id' => $printer,
                        'product_id' => $product['id'],
                    ];
                    $insert_data[] = $data;
                    $insert_data = collect($insert_data);
                    $chunks = $insert_data->chunk(100);
                    foreach ($chunks as $chunk)
                    {
                        DB::table('printer_product')->insert($chunk->toArray());
                    }
                }
            }

            $this->productUtil->createOrUpdateVariations($product, $request);

            if (!empty($request->consumption_details)) {
                $variations = $product->variations()->get();
                foreach ($variations as $variation) {
                    $this->productUtil->createOrUpdateRawMaterialToProduct($variation->id, $request->consumption_details);
                }
            }

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $product->addMedia($filePath)->toMediaCollection('product');
                }
            }



            if (!empty($request->supplier_id)) {
                SupplierProduct::updateOrCreate(
                    ['product_id' => $product->id, 'supplier_id' => $request->supplier_id]
                );
            }


            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
//        } catch (\Exception $e) {
//            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
//            $output = [
//                'success' => false,
//                'msg' => __('lang.something_went_wrong')
//            ];
//        }

        return $output;
    }

    public function getDiscountCustomerFromType($customer_types)
    {

        $discount_customers = [];
        if (!empty($customer_types)) {
            $customers = Customer::whereIn('customer_type_id', $customer_types)->get();
            foreach ($customers as $customer) {
                $discount_customers[] = $customer->id;
            }
        }

        return $discount_customers;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('product_module.product.view')) {
            abort(403, 'Unauthorized action.');
        }

        $product = Product::find($id);

        $stock_detials = ProductStore::where('product_id', $id)->get();

        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', '=', 'add_stock_lines.transaction_id')
            ->where('transactions.type', '=', 'add_stock')
            ->where('add_stock_lines.product_id', '=', $id)
            ->whereNotNull('add_stock_lines.expiry_date')
            ->select(
                'transactions.*',
                'add_stock_lines.expiry_date',
                DB::raw('SUM(add_stock_lines.quantity - add_stock_lines.quantity_sold) as current_stock')
            )->groupBy('add_stock_lines.id')
            ->get();

        return view('product.show')->with(compact(
            'product',
            'stock_detials',
            'add_stocks',
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
        if (!auth()->user()->can('product_module.product.create_and_edit')) {
            abort(403, 'Unauthorized action.');
        }
        $product = Product::with('variations')->findOrFail($id);

        $product_classes = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $sub_categories = Category::whereNotNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $units = Unit::where('is_raw_material_unit', 0)->orderBy('name', 'asc')->pluck('name', 'id','base_unit_multiplier');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $stores  = Store::all();

        $raw_materials  = Product::where('is_raw_material', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $raw_material_units  = Unit::orderBy('name', 'asc')->pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');
        $units_js=$units->pluck('base_unit_multiplier', 'id');
        return view('product.edit')->with(compact(
            'raw_materials',
            'raw_material_units',
            'product',
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
            'stores',
            'suppliers',
            'units_js'
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
        if (!auth()->user()->can('product_module.product.create_and_edit')) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['purchase_price' => ['required', 'max:25', 'decimal']],
            ['sell_price' => ['required', 'max:25', 'decimal']],
        );

        try {
            $product_data = [
                'name' => $request->name,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'product_class_id' => $request->product_class_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'sku' => $request->sku,
                'multiple_units' => $request->multiple_units,
                'multiple_colors' => $request->multiple_colors,
                'multiple_sizes' => $request->multiple_sizes,
                'multiple_grades' => $request->multiple_grades,
                'is_service' => !empty($request->is_service) ? 1 : 0,
                'product_details' => $request->product_details,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'other_cost' => !empty($request->other_cost) ? $this->commonUtil->num_uf($request->other_cost) : 0,
                'purchase_price' => $this->commonUtil->num_uf($request->purchase_price),
                'sell_price' => $this->commonUtil->num_uf($request->sell_price),
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'discount_type' => null,
                'discount_customer_types' => null,
                'discount_customers' => null,
                'discount' => null,
                'discount_start_date' => null,
                'discount_end_date' =>  null,
                'show_to_customer' => !empty($request->show_to_customer) ? 1 : 0,
                'show_to_customer_types' => $request->show_to_customer_types,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'this_product_have_variant' => !empty($request->this_product_have_variant) ? 1 : 0,
                'price_based_on_raw_material' => !empty($request->price_based_on_raw_material) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' => !empty($request->buy_from_supplier) ? 1 : 0,
                'type' => !empty($request->this_product_have_variant) ? 'variable' : 'single',
                'active' => !empty($request->active) ? 1 : 0,
                'have_weight' => !empty($request->have_weight) ? 1 : 0,
                'edited_by' => Auth::user()->id,
            ];


            DB::beginTransaction();
            $product = Product::find($id);
            $product->update($product_data);

            $this->productUtil->createOrUpdateVariations($product, $request);

            $index_discounts=[];
            $index_discounts_olds=[];
            if($request->discount_type){
                if(count($request->discount_type)>0){
                    $index_discounts=array_keys($request->discount_type);
                    if($request->discount_ids != null ){
                        $index_discounts_olds=array_keys($request->discount_ids);
                        ProductDiscount::where('product_id',$product->id)->whereNotIn('id',$request->discount_ids)->delete();
                    }else{
                        ProductDiscount::where('product_id',$product->id)->delete();
                    }
                }

                foreach ($index_discounts as $index_discount){
                    $discount_customers = $this->getDiscountCustomerFromType($request->get('discount_customer_types_'.$index_discount));
                    $data_des=[
                        'product_id' => $product->id,
                        'discount_type' => $request->discount_type[$index_discount],
                        'discount_category' => $request->discount_category[$index_discount],
                        'is_discount_permenant'=>!empty($request->is_discount_permenant[$index_discount])? 1 : 0,
                        'discount_customer_types' => $request->get('discount_customer_types_'.$index_discount),
                        'discount_customers' => $discount_customers,
                        'discount' => $this->commonUtil->num_uf($request->discount[$index_discount]),
                        'discount_start_date' => !empty($request->discount_start_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_start_date[$index_discount]) : null,
                        'discount_end_date' => !empty($request->discount_end_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_end_date[$index_discount]) : null
                    ];


                    if(in_array($index_discount,$index_discounts_olds)){
                        ProductDiscount::where('id',$request->discount_ids[$index_discount])->update($data_des);
                    }else{
                        ProductDiscount::create($data_des);
                    }


                }



            }else{
                ProductDiscount::where('product_id',$product->id)->delete();
            }

            if (!empty($request->consumption_details)) {
                $variations = $product->variations()->get();
                foreach ($variations as $variation) {
                    $this->productUtil->createOrUpdateRawMaterialToProduct($variation->id, $request->consumption_details);
                }
            }


//            if ($request->images) {
//                $product->clearMediaCollection('product');
//                foreach ($request->images as $image) {
//                    $product->addMedia($image)->toMediaCollection('product');
//                }
//            }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {
                    $product->clearMediaCollection('product');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $product->addMedia($filePath)->toMediaCollection('product');
                }
            }

            if (!empty($request->supplier_id)) {
                SupplierProduct::updateOrCreate(
                    ['product_id' => $product->id],
                    ['supplier_id' => $request->supplier_id]
                );
            } else {
                SupplierProduct::where('product_id', $product->id)->delete();
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

        if ($request->ajax()) {
            return $output;
        } else {
            return redirect()->back()->with('status', $output);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('product_module.product.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $variation = Variation::find($id);
            $variation_count = Variation::where('product_id', $variation->product_id)->count();
            if ($variation_count > 1) {

                $variation->delete();
                ProductStore::where('variation_id', $id)->delete();
                $output = [
                    'success' => true,
                    'msg' => __('lang.deleted')
                ];
            } else {
                ProductStore::where('product_id', $variation->product_id)->delete();
                $product = Product::where('id', $variation->product_id)->first();
                $product->clearMediaCollection('product');
                $product->delete();
                $variation->delete();
            }
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
            DB::commit();
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function getVariationRow()
    {
        
        $row_id = request()->row_id;
        //'base_unit_multiplier'
        $units = Unit::orderBy('name', 'asc');
        $units_js=$units->pluck('base_unit_multiplier', 'id');
        $units = $units->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $grades = Grade::orderBy('name', 'asc')->pluck('name', 'id');
        $stores = Store::all();
        $name = request()->name;
        $purchase_price = request()->purchase_price;
        $sell_price = request()->sell_price;
        $is_service = request()->is_service;

        return view('product.partial.variation_row')->with(compact(
            'units',
            'colors',
            'sizes',
            'grades',
            'stores',
            'row_id',
            'name',
            'purchase_price',
            'sell_price',
            'units_js',
            'is_service'
        ));
    }

    public function getProducts()
    {
        if (request()->ajax()) {

            $term = request()->term;

            if (empty($term)) {
                return json_encode([]);
            }

            $q = Product::leftJoin(
                'variations',
                'products.id',
                '=',
                'variations.product_id'
            )
                ->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%' . $term . '%');
                    $query->orWhere('sku', 'like', '%' . $term . '%');
                    $query->orWhere('sub_sku', 'like', '%' . $term . '%');
                })
                ->whereNull('variations.deleted_at')
                ->select(
                    'products.id as product_id',
                    'products.name',
                    'products.type',
                    // 'products.sku as sku',
                    'variations.id as variation_id',
                    'variations.name as variation',
                    'variations.sub_sku as sub_sku'
                );

            if (!empty(request()->store_id)) {
                $q->ForLocation(request()->store_id);
            }
            $products = $q->get();

            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->product_id]['name'] = $product->name;
                $products_array[$product->product_id]['sku'] = $product->sub_sku;
                $products_array[$product->product_id]['type'] = $product->type;
                $products_array[$product->product_id]['variations'][]
                    = [
                        'variation_id' => $product->variation_id,
                        'variation_name' => $product->variation,
                        'sub_sku' => $product->sub_sku
                    ];
            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {
                    if ($no_of_records > 1 && $value['type'] != 'single') {
                        $result[] = [
                            'id' => $i,
                            'text' => $value['name'] . ' - ' . $value['sku'],
                            'variation_id' => 0,
                            'product_id' => $key
                        ];
                    }
                    $name = $value['name'];
                    foreach ($value['variations'] as $variation) {
                        $text = $name;
                        if ($value['type'] == 'variable') {
                            if ($variation['variation_name'] != 'Default') {
                                $text = $text . ' (' . $variation['variation_name'] . ')';
                            }
                        }
                        $i++;
                        $result[] = [
                            'id' => $i,
                            'text' => $text . ' - ' . $variation['sub_sku'],
                            'product_id' => $key,
                            'variation_id' => $variation['variation_id'],
                        ];
                    }
                    $i++;
                }
            }

            return json_encode($result);
        }
    }

    /**
     * get the list of porduct purchases
     *
     * @param [type] $id
     * @return void
     */
    public function getPurchaseHistory($id)
    {
        $product = Product::find($id);
        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', 'add_stock_lines.transaction_id')
            ->where('add_stock_lines.product_id', $id)
            ->groupBy('transactions.id')
            ->select('transactions.*')
            ->get();

        return view('product.partial.purchase_history')->with(compact(
            'product',
            'add_stocks',
        ));
    }

    /**
     * get import page
     *
     */
    public function getImport()
    {

        return view('product.import');
    }

    /**
     * save import resource to stores
     *
     */
    public function saveImport(Request $request)
    {



        $this->validate($request, [
            'file' => 'required|mimes:csv,txt,xlsx'
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new ProductImport($this->productUtil, $request), $request->file);
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
/*            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
              return  $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }*/
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong') .' , '. __('lang.import_req')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * check sku if already in use
     *
     * @param string $sku
     * @return array
     */
    public function checkSku($sku)
    {
        $product_sku = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->where('sub_sku', $sku)->first();

        if (!empty($product_sku)) {
            $output = [
                'success' => false,
                'msg' => __('lang.sku_already_in_use')
            ];
        } else {
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        }

        return $output;
    }

    /**
     * check name if already in use
     *
     * @param string $name
     * @return array
     */
    public function checkName(Request $request)
    {
        $query = Product::where('name', $request->name);
        if (!empty($request->product_class_id)) {
            $query->where('product_class_id', $request->product_class_id);
        }
        if (!empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $product_name = $query->first();

        if (!empty($product_name)) {
            $output = [
                'success' => false,
                'msg' => __('lang.name_already_in_use')
            ];
        } else {
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        }

        return $output;
    }

    public function deleteProductImage($id)
    {
        try {
            $product = Product::find($id);
            $product->clearMediaCollection('product');

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
     * get raw material row
     *
     * @return void
     */
    public function getRawMaterialRow()
    {
        $row_id = request()->row_id ?? 0;
        $raw_materials  = Product::where('is_raw_material', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $raw_material_units  = Unit::orderBy('name', 'asc')->pluck('name', 'id');

        return view('product.partial.raw_material_row')->with(compact(
            'row_id',
            'raw_materials',
            'raw_material_units',
        ));
    }
 /**
     * get raw material row
     *
     * @return void
     */
    public function getRawDiscount()
    {
        $row_id = request()->row_id ?? 0;
        $discount_customer_types = CustomerType::pluck('name', 'id');

        return view('product.partial.raw_discount')->with(compact(
            'row_id',
            'discount_customer_types',
        ));
    }

    /**
     * get raw material details
     *
     * @param int $raw_material_id
     * @return void
     */
    public function getRawMaterialDetail($raw_material_id)
    {
        $raw_material = Product::find($raw_material_id);

        return ['raw_material' => $raw_material];
    }
    public function getBase64Image($Image)
    {

        $image_path = str_replace(env("APP_URL") . "/", "", $Image);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_content = curl_exec($ch);
        curl_close($ch);
//    $image_content = file_get_contents($image_path);
        $base64_image = base64_encode($image_content);
        $b64image = "data:image/jpeg;base64," . $base64_image;
        return  $b64image;
    }
    public function getCroppedImages($cropImages){
        $dataNewImages = [];
        foreach ($cropImages as $img) {
            if (strlen($img) < 200){
                $dataNewImages[] = $this->getBase64Image($img);
            }else{
                $dataNewImages[] = $img;
            }
        }
        return $dataNewImages;
    }
}
