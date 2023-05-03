@extends('layouts.app')
@section('title', __('lang.product'))

@section('content')
{{-- <form  id="product_form" method="POST" action="{{route('add_product_adjustment')}}"> --}}
    {{-- @csrf --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                
                <button type="button" value="10" class="badge badge-pill badge-primary column-toggle">
                    @if (session('system_mode') == 'restaurant')
                        @lang('lang.category')
                    @else
                        @lang('lang.class')
                    @endif
                </button>
                @if (session('system_mode') != 'restaurant')
                    <button type="button" value="11"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.category')</button>
                    <button type="button" value="12"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.sub_category')</button>
                @endif
                <button type="button" value="13"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.purchase_history')</button>
                <button type="button" value="14"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.batch_number')</button>
                <button type="button" value="15"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.selling_price')</button>
                <button type="button" value="16"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.tax')</button>
                @if (session('system_mode') != 'restaurant')
                    <button type="button" value="17"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.brand')</button>
                @endif
                <button type="button" value="18"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.unit')</button>
                <button type="button" value="19"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.color')</button>
                <button type="button" value="20"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.size')</button>
                <button type="button" value="21"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.grade')</button>
                @if (empty($page))
                    <button type="button" value="5"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.current_stock')</button>
                @endif
                @if (!empty($page))
                    <button type="button" value="22"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.current_stock_value')</button>
                @endif
                <button type="button" value="23"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.customer_type')</button>
                <button type="button" value="24"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.expiry_date')</button>
                <button type="button" value="25"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.manufacturing_date')</button>
                <button type="button" value="26"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.discount')</button>
                @can('product_module.purchase_price.view')
                    <button type="button" value="9"
                        class="badge badge-pill badge-primary column-toggle">@lang('lang.purchase_price')</button>
                @endcan
                <button type="button" value="27"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.supplier')</button>
                <button type="button" value="28"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.active')</button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="product_table" class="table" style="width: auto">
            <thead>
                <tr class="input-row">
                    <th></th>
                    <th></th>
                    <th>@lang('lang.image')</th>
                    <th style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@lang('lang.name')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>@lang('lang.product_code')</th>
                    <th class="sum">@lang('lang.current_stock')</th>
                    <th>@lang('lang.actual_stock')</th>
                    <th>@lang('lang.shortage')</th>
                    <th>@lang('lang.value_of_shortage')</th>
                    @can('product_module.purchase_price.view')
                        <th>@lang('lang.purchase_price')</th>
                    @endcan
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
                    <th>@lang('lang.selling_price')</th>
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
    <input hidden value="" name="total_shortage_value" id="total_shortage_value">
    <button data-check_password="{{ action('UserController@checkAdminPassword',2 ) }}" class="btn btn-primary check_pass">Save</button>
    <button data-check_password="{{ action('UserController@checkAdminPassword',2 ) }}" class="check_pass btn btn-primary"  onclick="printTable()" >Print Table</button>

    {{-- </form> --}}
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            // $('.column-toggle').each(function(i, obj) {
            //     if (i > 0) {
            //         i = i + 2;
            //     }
            //     @if (session('system_mode') != 'restaurant')
            //         @if (empty($page))
            //             if (i > 15) {
            //                 i = i + 1;
            //             }
            //         @else
            //             if (i > 14) {
            //                 i = i + 1;
            //             }
            //         @endif
            //     @else
            //         @if (empty($page))
            //             if (i > 12) {
            //                 i = i + 1;
            //             }
            //         @else
            //             if (i > 11) {
            //                 i = i + 1;
            //             }
            //         @endif
            //     @endif
            //     $(obj).val(i)
            // });
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
                // processing: true,
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
                        // d.shortage = true;
                        // d.shortage_value = true;
                    },
                },
                columnDefs: [{
                    "targets": [0, 3],
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
                    },
                    {
                        data: 'variation_id', 
                        'render': function (data, type, val, meta){
                            return '<span hidden type="text" readonly="readonly" class="variation_id" name="variation_id" />'+data+'</span>';
                        },
                        // visible: false
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
                            return '<span type="text" readonly="readonly" class="current_stock" name="current_stock" />'+data+'</span>';
                        },
                        className: "current_stock",
                        searchable: false
                    },
                    {
                        name: 'actual_stock',
                        // type:  "text",
                        'render': function (data, type, val, meta){
                            return '<input type="text" class="actual_stock" name="actual_stock"  value="">';
                        },
                        searchable: false
                    },
                    {
                        name: 'shortage',
                        'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="shortage" name="shortage"  /></span>';
                        },
                        searchable: false
                    },
                    {
                        name: 'shortage_value',
                        'render': function (data, type, val, meta){
                            return '<span type="text" readonly="readonly" class="shortage_value" name="shortage_value"  /></span>';
                        },
                        searchable: false
                    },
                    @can('product_module.purchase_price.view')
                        {
                            data: 'default_purchase_price',
                            name: 'default_purchase_price',
                            className: "default_purchase_price",
                            searchable: false
                        },
                    @endcan
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
                        data: 'default_sell_price',
                        name: 'variations.default_sell_price'
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

        var hidden_column_array = $.cookie('column_visibility') ? JSON.parse($.cookie('column_visibility')) : [];
        $(document).ready(function() {

            $.each(hidden_column_array, function(index, value) {
                $('.column-toggle').each(function() {
                    if ($(this).val() == value) {
                        toggleColumnVisibility(value, $(this));
                    }
                });

            });
        });

        $(document).on('click', '.column-toggle', function() {
            let column_index = parseInt($(this).val());
            toggleColumnVisibility(column_index, $(this));
            if (hidden_column_array.includes(column_index)) {
                hidden_column_array.splice(hidden_column_array.indexOf(column_index), 1);
            } else {
                hidden_column_array.push(column_index);
            }

            //unique array javascript
            hidden_column_array = $.grep(hidden_column_array, function(v, i) {
                return $.inArray(v, hidden_column_array) === i;
            });

            $.cookie('column_visibility', JSON.stringify(hidden_column_array));
        })

        function toggleColumnVisibility(column_index, this_btn) {
            column = product_table.column(column_index);
            console.log(column_index);
            column.visible(!column.visible());

            if (column.visible()) {
                $(this_btn).addClass('badge-primary')
                $(this_btn).removeClass('badge-warning')
            } else {
                $(this_btn).removeClass('badge-primary')
                $(this_btn).addClass('badge-warning')

            }
        }
        $(document).on('change', '.filter_product', function() {
            product_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function() {
            $('.filter_product').val('');
            $('.filter_product').selectpicker('refresh');
            $('#product_id').val('');
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

            // function to update the total value in the HTML element
            function updateTotal() {
                const totalElement = document.getElementById('total');
                const totalElementinput = document.getElementById('total_shortage_value');
                totalElement.textContent = total;
                totalElementinput.value = total;
            }

            // function to calculate the end value for each row and update the total
            function calculateTotal() {
                total = 0;
                $("#product_table tbody tr").each(function() {
                var current_stock = parseInt($(this).find(".current_stock").text());
                var actual_stock = parseInt($(this).find(".actual_stock").val());
                var purchase_price = parseFloat($(this).find(".default_purchase_price").text());
                var shortage = current_stock - actual_stock;
                var shortage_val = shortage * purchase_price;
                if (!isNaN(shortage_val)) {
                    total += shortage_val;
                    $(this).find(".shortage").text(shortage);
                    $(this).find(".shortage_value").text(shortage_val);
                }
                });
                updateTotal();
            }

            // calculate the total initially
            calculateTotal();

            // add event listener to parent element (table)
            $("#product_table").on("input", ".actual_stock", function() {
                calculateTotal();
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
            });

            // Send the data to the server
            $.ajax({
                type: 'POST',
                url: '/product-in-adjustment-store',
                data: {selected_data: selectedData,
                    total_shortage_value: total_shortage_value},
                success: function(response) {
                console.log('Data sent successfully');
                location.reload();
                },
                error: function(xhr, status, error) {
                console.log('Error sending data');
                }
            });

        }
   
        





        
    </script>
@endsection