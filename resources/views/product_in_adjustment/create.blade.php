@extends('layouts.app')
@section('title', __('lang.product'))

@section('content')
{{-- <form  id="product_form" method="POST" action="{{route('add_product_adjustment')}}"> --}}
    {{-- @csrf --}}
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('product_class_id', session('system_mode') == 'restaurant' ? __('lang.category') : __('lang.product_class') . ':', []) !!}
                            {!! Form::select('product_class_id', $product_classes, request()->product_class_id, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    @if (session('system_mode') != 'restaurant')
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('category_id', __('lang.category') . ':', []) !!}
                                {!! Form::select('category_id', $categories, request()->category_id, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('sub_category_id', __('lang.sub_category') . ':', []) !!}
                                {!! Form::select('sub_category_id', $sub_categories, request()->sub_category_id, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('brand_id', __('lang.brand') . ':', []) !!}
                                {!! Form::select('brand_id', $brands, request()->brand_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('supplier_id', __('lang.supplier') . ':', []) !!}
                            {!! Form::select('supplier_id', $suppliers, request()->supplier_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('unit_id', __('lang.unit') . ':', []) !!}
                            {!! Form::select('unit_id', $units, request()->unit_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('color_id', __('lang.color') . ':', []) !!}
                            {!! Form::select('color_id', $colors, request()->color_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('size_id', __('lang.size') . ':', []) !!}
                            {!! Form::select('size_id', $sizes, request()->size_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('grade_id', __('lang.grade') . ':', []) !!}
                            {!! Form::select('grade_id', $grades, request()->grade_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('tax_id', __('lang.tax') . ':', []) !!}
                            {!! Form::select('tax_id', $taxes, request()->tax_id, [
    'class' => 'form-control
                        filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('store_id', __('lang.store'), []) !!}
                            {!! Form::select('store_id', $stores, request()->store_id, ['class' => 'form-control filter_product', 'data-live-search' => 'true']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('customer_type_id', __('lang.customer_type') . ':', []) !!}
                            {!! Form::select('customer_type_id', $customer_types, request()->customer_type_id, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('created_by', __('lang.created_by') . ':', []) !!}
                            {!! Form::select('created_by', $users, request()->created_by, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('active', __('lang.active') . ':', []) !!}
                            {!! Form::select('active', [0 => __('lang.no'), 1 => __('lang.yes')], request()->active, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{-- <label>
                                Don't show zero stocks
                            </label> --}}
                            {!! Form::label('show_zero_stocks',"Don't show zero stocks" . ':') !!}
                            {!! Form::checkbox('show_zero_stocks', 1, false, ['class' => ' form-control  show_zero_stocks','data-live-search' => 'true',
                            ], request()->show_zero_stocks ? true : false) !!}
                            
                            
                        </div>
                    </div>
                    <input type="hidden" name="product_id" id="product_id" value="">
                    <div class="col-md-3">
                        <button class="btn btn-danger mt-4 clear_filters">@lang('lang.clear_filters')</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <button type="button" value="15" class="badge badge-pill badge-primary column-toggle">
                    @if (session('system_mode') == 'restaurant')
                        @lang('lang.category')
                    @else
                        @lang('lang.class')
                    @endif
                </button>
                @if (session('system_mode') != 'restaurant')
                    <button type="button" value="16"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.category')</button>
                    <button type="button" value="17"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.sub_category')</button>
                @endif
                <button type="button" value="18"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.purchase_history')</button>
                <button type="button" value="19"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.batch_number')</button>
                <button type="button" value="13"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.selling_price')</button>
                <button type="button" value="20"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.tax')</button>
                @if (session('system_mode') != 'restaurant')
                    <button type="button" value="21"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.brand')</button>
                @endif
                <button type="button" value="22"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.unit')</button>
                <button type="button" value="23"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.color')</button>
                <button type="button" value="24"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.size')</button>
                <button type="button" value="25"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.grade')</button>
                @if (empty($page))
                    <button type="button" value="6"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.current_stock')</button>
                @endif
                @if (!empty($page))
                    <button type="button" value="26"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.current_stock_value')</button>
                @endif
                <button type="button" value="27"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.customer_type')</button>
                <button type="button" value="28"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.expiry_date')</button>
                <button type="button" value="29"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.manufacturing_date')</button>
                <button type="button" value="30"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.discount')</button>
                @can('product_module.purchase_price.view')
                    <button type="button" value="11"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.purchase_price')</button>
                    {{-- <button type="button" value="9"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.avg_purchase_price')</button> --}}
                @endcan
                <button type="button" value="31"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.supplier')</button>
                <button type="button" value="32"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.active')</button>
            </div>
        </div>
    </div>
    <div style="text-align: center;">
        <p style="color: rgb(219, 76, 76)">@lang('lang.check_purchase_price_please')</p>
    </div>
    <div class="table-responsive">
        <table id="product_table" class="table" style="width: auto">
            <thead>
                <tr class="input-row">
                    <th></th>
                    <th></th>
                    <th>@lang('lang.remove_from_expenses')</th>
                    <th>@lang('lang.image')</th>
                    <th style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('lang.name')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>@lang('lang.product_code')</th>
                    <th class="sum">@lang('lang.current_stock')</th>
                    <th>@lang('lang.actual_stock')</th>
                    <th>@lang('lang.shortage')</th>
                    <th>@lang('lang.value_of_shortage')</th>
                    @can('product_module.purchase_price.view')
                        <th>@lang('lang.avg_purchase_price')</th>
                        <th>@lang('lang.purchase_price')</th>  
                        <th></th>
                    @endcan
                    <th>@lang('lang.selling_price')</th>
                    <th></th> 
                    <th>
                        @if (session('system_mode') == 'restaurant')
                            @lang('lang.category')
                        @else
                            @lang('lang.class')
                        @endif
                    </th>
                    @if (session('system_mode') != 'restaurant')
                        <th>@lang('lang.category')</th>
                        <th>@lang('lang.sub_category')</th>
                    @endif
                    <th>@lang('lang.purchase_history')</th>
                    <th>@lang('lang.batch_number')</th>
                    
                    <th>@lang('lang.tax')</th>
                    @if (session('system_mode') != 'restaurant')
                        <th>@lang('lang.brand')</th>
                    @endif
                    <th>@lang('lang.unit')</th>
                    <th>@lang('lang.color')</th>
                    <th>@lang('lang.size')</th>
                    <th>@lang('lang.grade')</th>
                    <th class="sum">@lang('lang.current_stock_value')</th>
                    <th>@lang('lang.customer_type')</th>
                    <th>@lang('lang.expiry_date')</th>
                    <th>@lang('lang.manufacturing_date')</th>
                    <th>@lang('lang.discount')</th>
                    
                    <th>@lang('lang.supplier')</th>
                    <th>@lang('lang.active')</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>   
                    <th style="text-align: right">@lang('lang.total')</th>
                    <td></td>
                    <td></td>
                    
                    <td></td>

                    <td>@lang('lang.total_shortage_value')</td>
                    <td></td>
                    <td id="total"></td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <input type="hidden" id="des" value="{{\App\Models\System::getProperty('numbers_length_after_dot')}}" />
    <input hidden value="" name="total_shortage_value" id="total_shortage_value">
     <input hidden value="" name="expenses_total_shortage_value" id="expenses_total_shortage_value">
    <button data-check_password="{{ action('UserController@checkAdminPassword',2 ) }}" class="btn btn-primary check_pass">Save</button>
    <button data-check_password="{{ action('UserController@checkAdminPassword',2 ) }}" class="check_pass btn btn-primary"  onclick="printTable()" >Print Table</button>

    {{-- </form> --}}
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
 
            var actualStockColIndex = null;
            var currentStockColIndex = null;
            product_table = $('#product_table').DataTable({
                
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                // stateSave: true,
                buttons:buttons,
                processing: true,
                serverSide: true,
                aaSorting: [
                    [2, 'asc']
                ],
                "ajax": {
                    "url": "/product-in-adjustment-create",
                    "data": function(d) {
                        d.product_id = $('#product_id').val();
                        d.product_class_id = $('#product_class_id').val();
                        d.category_id = $('#category_id').val();
                        d.sub_category_id = $('#sub_category_id').val();
                        d.brand_id = $('#brand_id').val();
                        d.supplier_id = $('#supplier_id').val();
                        d.unit_id = $('#unit_id').val();
                        d.color_id = $('#color_id').val();
                        d.size_id = $('#size_id').val();
                        d.grade_id = $('#grade_id').val();
                        d.tax_id = $('#tax_id').val();
                        d.store_id = $('#store_id').val();
                        d.customer_type_id = $('#customer_type_id').val();
                        d.active = $('#active').val();
                        d.created_by = $('#created_by').val();
                        d.show_zero_stocks = $('#show_zero_stocks').val();
                        // d.shortage = true;
                        // d.shortage_value = true;
                    },
                },
                columnDefs: [{
                    "targets": [2, 10],  
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    {
                        data: 'id', 
                        'render': function (data, type, val, meta){
                            return '<span hidden type="text" readonly="readonly" class="pro_id" name="product_id" />'+data+'</span>';
                        },
                        // visible: false
                        sortable: false,
                    },
                    {
                        data: 'variation_id', 
                        'render': function (data, type, val, meta){
                            return '<span hidden type="text" readonly="readonly" class="variation_id" name="variation_id" />'+data+'</span>';
                        },
                        // visible: false
                        searchable: false,
                        sortable: false
                    },
                    {
                        name: 'product_selected_expenses',
                        'render': function (data, type, val, meta){
                            return '<input type="checkbox" class="product_selected_expenses" name="product_selected_expenses" />';
                        },
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'variation_name',
                        name: 'products.name'
                    },
                    {
                        data: 'sub_sku',
                        name: 'variations.sub_sku'
                    },
                    {
                        data: 'current_stock',
                        name: 'current_stock',
                        'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="current_stock" name="current_stock" />'+data+'</span> '+
                           '<input type="hidden"  class="current_stock_hidden"  value="'+data+'">';
                        },
                        className: "current_stock",
                        searchable: false
                    },
                    {
                        name: 'actual_stock',
                        // type:  "text",
                        'render': function (data, type, val, meta){
                            return '<input type="text" class="actual_stock " name="actual_stock"  value="">';
                        },
                        searchable: false,
                        sortable: false
                    },
                    {
                        name: 'shortage',
                        'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="shortage" name="shortage"  /></span>';
                        },
                        searchable: false,
                        sortable: false
                    },
                    {
                        name: 'shortage_value',
                        'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="shortage_value" name="shortage_value"  /></span>';
                        },
                        searchable: false,
                        sortable: false
                    },
                    @can('product_module.purchase_price.view')
                        {
                            data: 'avg_purchase_price',
                            name: 'avg_purchase_price',
                            'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="avg_purchase_price" name="avg_purchase_price" />'+data+'</span>';
                            },
                            className: "avg_purchase_price",
                            searchable: false
                        },

                        {
                            data: 'default_purchase_price',
                            name: 'default_purchase_price',
                            // className: "default_purchase_price",
                            'render': function (data, type, val, meta){
                                return '<input type="text" class="default_purchase_price" name="default_purchase_price"  value="'+data+'">';
                            },
                            searchable: false
                        },
                        {
                            data: 'default_purchase_price',
                            name: 'default_purchase_price',
                            // className: "default_purchase_price",
                            'render': function (data, type, val, meta){
                                return '<input hidden type="text" class="hidden_default_purchase_price"  value="'+data+'">';
                            },
                            searchable: false,
                            // visible : false,
                        },
                    @endcan
                    {
                        data: 'default_sell_price',
                        name: 'variations.default_sell_price',
                        'render': function (data, type, val, meta){
                                return '<input type="text" class="default_sell_price" name="default_sell_price"  value="'+data+'">';
                        },
                    },
                    {
                            data: 'default_sell_price',
                            name: 'default_sell_price',
                            // className: "default_purchase_price",
                            'render': function (data, type, val, meta){
                                return '<input hidden type="text" class="hidden_default_sell_price"  value="'+data+'">';
                            },
                            searchable: false,
                            // visible : false,
                        },
                    {
                        data: 'product_class',
                        name: 'product_classes.name'
                    },
                    @if (session('system_mode') != 'restaurant')
                        {
                            data: 'category',
                            name: 'categories.name'
                        }, {
                            data: 'sub_category',
                            name: 'categories.name'
                        },
                    @endif {
                        data: 'purchase_history',
                        name: 'purchase_history'
                    },
                    {
                        data: 'batch_number',
                        name: 'add_stock_lines.batch_number'
                    },
                    
                    {
                        data: 'tax',
                        name: 'taxes.name'
                    },
                    @if (session('system_mode') != 'restaurant')
                        {
                            data: 'brand',
                            name: 'brands.name'
                        },
                    @endif {
                        data: 'unit',
                        name: 'units.name'
                    },
                    {
                        data: 'color',
                        name: 'colors.name'
                    },
                    {
                        data: 'size',
                        name: 'sizes.name'
                    },
                    {
                        data: 'grade',
                        name: 'grades.name'
                    },
                    {
                        data: 'current_stock_value',
                        name: 'current_stock_value',
                        searchable: false
                        @if (empty($page))
                            , visible: false
                        @endif
                    },
                    {
                        data: 'customer_type',
                        name: 'customer_type'
                    },
                    {
                        data: 'exp_date',
                        name: 'add_stock_lines.expiry_date'
                    },
                    {
                        data: 'manufacturing_date',
                        name: 'add_stock_lines.manufacturing_date'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                        searchable: false
                    },
                    {
                        data: 'active',
                        name: 'active'
                    },

                ],
                createdRow: function(row, data, dataIndex) {

                },
                fnDrawCallback: function(oSettings) {
                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                            i :
                            0;
                    };

                    this.api()
                        .columns(".sum", {
                            page: "current"
                        })
                        .every(function() {
                            var column = this;
                            if (column.data().count()) {
                                var sum = column.data().reduce(function(a, b) {
                                    a = intVal(a);
                                    if (isNaN(a)) {
                                        a = 0;
                                    }

                                    b = intVal(b);
                                    if (isNaN(b)) {
                                        b = 0;
                                    }

                                    return a + b;
                                });
                                $(column.footer()).html(
                                    __currency_trans_from_en(sum, false)
                                );
                            }
                        });
                },
            });

        });


        function printTable() {
            // var printContents = document.getElementById('product_table').outerHTML;
            // var originalContents = document.body.innerHTML;
            // document.body.outerHTML = printContents;
            // window.print();
            // // document.body.innerHTML = originalContents;
            // sendData();
            var divToPrint=document.getElementById("product_table");
            newWin= window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }

        $(document).ready(function() {
            var hiddenColumnArray = JSON.parse('{!! addslashes(json_encode(Cache::get("key_" . auth()->id(), []))) !!}');

            $.each(hiddenColumnArray, function(index, value) {
                $('.column-toggle').each(function() {
                if ($(this).val() == value) {
                    // alert(value)
                    toggleColumnVisibility(value, $(this));
                }
                });
            });

            $(document).on('click', '.column-toggle', function() {
                var column_index = parseInt($(this).val());
                toggleColumnVisibility(column_index, $(this));

                if (hiddenColumnArray.includes(column_index)) {
                hiddenColumnArray.splice(hiddenColumnArray.indexOf(column_index), 1);
                } else {
                hiddenColumnArray.push(column_index);
                }

                hiddenColumnArray = [...new Set(hiddenColumnArray)]; // Remove duplicates

                // Update the columnVisibility cache data
                $.ajax({
                url: '/update-column-visibility', // Replace with your route or endpoint for updating cache data
                method: 'POST',
                data: { columnVisibility: hiddenColumnArray },
                    success: function() {
                        console.log('Column visibility updated successfully.');
                    }
                });
            });

            function toggleColumnVisibility(column_index, this_btn) {
                var column = product_table.column(column_index);
                column.visible(!column.visible());

                if (column.visible()) {
                $(this_btn).addClass('badge-primary').removeClass('badge-warning');
                } else {
                $(this_btn).removeClass('badge-primary').addClass('badge-warning');
                }
            }
        });
        $(document).on('change', '.filter_product', function() {
            product_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function() {
            $('.filter_product').val('');
            $('.filter_product').selectpicker('refresh');
            $('#product_id').val('');
            $('.show_zero_stocks').val(1);
            product_table.ajax.reload();
        });
        $(document).on('change', '.show_zero_stocks', function() {
            if(this.checked) {
                $('.show_zero_stocks').val(0);
            }else{
                $('.show_zero_stocks').val(1);
            }
            product_table.ajax.reload();
        });

        @if (!empty(request()->product_id))
            $(document).ready(function() {
                $('#product_id').val({{ request()->product_id }});
                product_table.ajax.reload();

                var container = '.view_modal';
                $.ajax({
                    method: 'get',
                    url: '/product/{{ request()->product_id }}',
                    dataType: 'html',
                    success: function(result) {
                        $(container).html(result).modal('show');
                    },
                });
            });
        @endif

        $(document).on('click', '.check_pass', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.adjustment_save')",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    // var href = $(this).data('href');
                    var data = $(this).serialize();

                    swal({
                        title: 'Please Enter Your Password',
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your password",
                                type: "password",
                                autocomplete: "off",
                                autofocus: false,
                            },
                        },
                        inputAttributes: {
                            autocapitalize: 'off',
                            autoComplete: 'off',
                        },
                        focusConfirm: true
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        swal(
                                            'Success',
                                            'Correct Password!',
                                            'success'
                                        );

                                       sendData();

                                    } else {
                                        swal(
                                            'Failed!',
                                            'Wrong Password!',
                                            'error'
                                        )

                                    }
                                }
                            });
                        }
                    });
                }
            });
        });
        

        $(document).ready(function() {
            var total = 0;
            // Call the updateTotal function when the checkbox state changes
            $(document).on('change', '.product_selected_expenses', function() {
                updateTotal();
            });
            // function to update the total value in the HTML element
            function updateTotal() {
                // loop through all the valid rows and calculate the sum of their shortage values
                var sum = 0;
                var expenses_sum = 0 ;
                $("#product_table tbody tr").each(function() {
                    var shortage_val = parseFloat($(this).find(".shortage_value").text());
                    var isRowSelected = $(this).find(".product_selected_expenses").prop("checked");
                    if (isRowSelected) {
                        var shortage_val = parseFloat($(this).find(".shortage_value").text());
                        if (!isNaN(shortage_val)) {
                            expenses_sum += shortage_val;
                        }
                    }
                    if (!isNaN(shortage_val)) {
                        sum += shortage_val;
                    }
                });
                total = sum;
                expenses_total = expenses_sum;
                // update the total value in the HTML element
                const totalElement = document.getElementById('total');
                const totalElementinput = document.getElementById('total_shortage_value');
                const expenses_total_shortage_value = document.getElementById('expenses_total_shortage_value');
                totalElement.textContent = total.toFixed(2);
                totalElementinput.value = total.toFixed(2);
                expenses_total_shortage_value.value = expenses_total.toFixed(2);
            }

            // function to calculate the end value for each row and update the total
            function calculateTotal(tr) {
                // total = 0;
                // var tr = $(this).closest('tr');
                var des = document.getElementById("des").value;
                console.log(tr);
                var current_stock = __read_number(tr.find(".current_stock_hidden"));
                var actual_stock = tr.find(".actual_stock").val();
                var purchase_price = parseFloat(tr.find(".avg_purchase_price").text()).toFixed(2);
                var shortage = 0 ;
                var shortage_val = 0;
                if (actual_stock != "") {
                     shortage = (current_stock - actual_stock);
                     shortage_val = (current_stock - actual_stock) * purchase_price;
                }
                
                if (!isNaN(shortage_val)) {
                    // total += parseFloat(shortage_val);
                    tr.find(".shortage").text(shortage.toFixed(des));
                    tr.find(".shortage_value").text(shortage_val.toFixed(2));
                    console.log("total :"+ total);
                }
                updateTotal();
            }

            // // calculate the total initially
            // calculateTotal();
            $('#product_table').on('change', '.default_purchase_price, .default_sell_price', function() {
                var row = $(this).closest('tr');
                var purchasePrice = parseFloat(row.find('.default_purchase_price').val()) || 0;
                var sellPrice = parseFloat(row.find('.default_sell_price').val()) || 0;

                // Check if default_purchase_price is greater than or equal to default_sell_price
                if (purchasePrice >= sellPrice) {
                    row.find('.default_purchase_price').css('border', '2px solid red');
                    row.find('.default_sell_price').css('border', '2px solid red');
                } else {
                    row.find('.default_purchase_price').css('border', '');
                    row.find('.default_sell_price').css('border', ''); // Reset border if condition is not met
                }
            });
            // add event listener to parent element (table)
            $("#product_table").on("input", ".actual_stock", function() {
                var tr = $(this).closest('tr');
                calculateTotal(tr);
            });
        

            // add event listener to change event on actual_stock input field
            $("#product_table").on("input", ".actual_stock", function() {
                if ($(this).val() === "") {
                var row = $(this).closest("tr");
                row.find(".shortage").text("");
                row.find(".shortage_value").text("");
                calculateTotal();
                }
            });
        });

        function sendData() {
            // Get the table instance
            var table = $('#product_table').DataTable();

            // Initialize an empty array to store the selected data
            var selectedData = [];
            var total_shortage_value = document.getElementById('total_shortage_value').value;
            var expenses_total_shortage_value = document.getElementById('expenses_total_shortage_value').value;
            // Loop through each row in the table
            table.rows().every(function() {
                var rowData = this.row().data();
                var actualStock = $('input[name="actual_stock"]', this.node()).val();
                var current_stock = $('span[name="current_stock"]', this.node()).text();
                var shortage = $('span[name="shortage"]', this.node()).text();
                var shortage_value = $('span[name="shortage_value"]', this.node()).text();
                var id = $('span[name="product_id"]', this.node()).text();
                var variation_id = $('span[name="variation_id"]', this.node()).text();
                console.log(variation_id);
                // Check if actualStock has a value
                if (actualStock != '') {
                    validActualStock = true;
                    // Add the required data to the selectedData array
                    var dataObj = {
                        id: id,
                        variation_id : variation_id,
                        current_stock: current_stock,
                        actual_stock: actualStock,
                        shortage: shortage,
                        shortage_value: shortage_value
                    };
                    selectedData.push(dataObj);
                }
                var data = this.data();
                var purchasePrice = parseFloat($(this.node()).find('.default_purchase_price').val());
                var sellPrice = parseFloat($(this.node()).find('.default_sell_price').val());
                var purchasePriceHidden = parseFloat($(this.node()).find('.hidden_default_purchase_price').val());
                var sellPriceHidden = parseFloat($(this.node()).find('.hidden_default_sell_price').val());
                console.log("purchasePrice :" + purchasePrice)
                console.log("purchasePriceHidden :" + purchasePriceHidden)
                console.log("sellPrice :" + sellPrice)
                console.log("sellPriceHidden :" + sellPriceHidden)
                    // Check if either the purchase price or sell price has changed
                    if (purchasePrice !== purchasePriceHidden || sellPrice !== sellPriceHidden) {
                        if(purchasePrice > sellPrice){
                            $(this).find('.default_purchase_price').css('border', '2px solid #6f42c1');
                            $(this).find('.default_sell_price').css('border', '2px solid #6f42c1');
                        }
                        // Create an object with the updated values
                        if(purchasePrice !== 0 && sellPrice !== 0){
                            var updatedData = {
                                id: id,
                                variation_id : variation_id,
                                old_purchase_price: purchasePriceHidden,
                                default_purchase_price: purchasePrice,
                                old_sell_price: sellPriceHidden,
                                default_sell_price: sellPrice
                            };
                            // Add the updated data to the selectedData array
                            selectedData.push(updatedData);
                        }else{
                            swal(
                                            'Success!',
                                            "New stock quantity saved, Zero prices didn't save",
                                            'success'
                                        );
                        }
                        
                    }

            });

            // // // Check if either purchase price or sell price is 0
            // var invalidPrices = selectedData.some(function(data) {
            //     return (data.default_purchase_price === 0 || data.default_sell_price === 0);
            // });


            // If invalid prices found, show the alert and do not send data
       
                //  Send the data to the server
                $.ajax({
                    type: 'POST',
                    url: '/product-in-adjustment-store',
                    data: {
                        selected_data: selectedData,
                        total_shortage_value: total_shortage_value,
                        expenses_total_shortage_value: expenses_total_shortage_value
                    },
                    success: function(response) {
                        console.log('Data sent successfully');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log('Error sending data');
                    }
                });
            // }

        }
        





        
    </script>
@endsection
