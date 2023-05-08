@extends('layouts.app')
@section('title', __('lang.product'))

@section('content')


    <input type="hidden" id="productIdRequest"  value="{{ request("product_id") }}">

    <div class="table-responsive">
        <table id="product_table" class="table" style="width: auto">
            <thead>
            <tr class="input-row">
                <th></th>
                <th></th>
                <th>@lang('lang.image')</th>
                <th style="">@lang('lang.name')</th>
                <th>@lang('lang.product_code')</th>
                <th class="sum">@lang('lang.current_stock_expired')</th>
                <th>@lang('lang.quantity_to_be_removed')</th>
                <th>@lang('lang.date_of_expired_stock')</th>
                <th>@lang('lang.date_of_purchase_of_the_expired_stock')</th>
                <th>@lang('lang.avg_purchase_price')</th>
                <th>@lang('lang.value_of_removed_stock')</th>
            </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
    <input hidden value="" name="total_shortage_value" id="total_shortage_value">
    <button style="margin-left: 25px" data-check_password="{{ action('UserController@checkAdminPassword',2 ) }}" class="btn btn-primary check_pass">Save</button>
    <a style="margin-left: 25px" id="cancel_btn" class="btn btn-danger text-white ">Cancel</a>

    {{-- </form> --}}
@endsection

@section('javascript')
    <script>


        $(document).ready(function() {
            var currentUrl = window.location.href;
            let productId = currentUrl.match(/product_id=(\d+)/)[1];
            $("#cancel_btn").on("click",function(){
                $("#cancel_btn").attr("href","/product/remove_expiry/"+productId+"");
            });
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
                        "url": "/product/create/product_id="+productId+"/convolutions",
                        "data": function(d) {
                            d.product_id = productId;
                            d.store_id = $('#store_id').val();

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
                            data: 'expired_current_stock',
                            name: 'expired_current_stock',
                            'render': function (data, type, val, meta){
                                return '<span type="text" readonly="readonly" class="expired_current_stock" name="expired_current_stock" />'+data+'</span>';
                            },
                            className: "expired_current_stock",
                            searchable: false
                        },
                        {
                            name: 'quantity_to_be_removed',
                            // type:  "text",
                            'render': function (data, type, val, meta){
                                return '<input type="text" class="quantity_to_be_removed" name="quantity_to_be_removed" >';
                            },
                            searchable: false
                        },
                        {
                            data:"exp_date",
                            name:"add_stock_lines.expiry_date"
                        },
                        {
                            data:"date_of_purchase_of_the_expired_stock_removed",
                            name:"add_stock_lines.created_at",
                            'render': function (data, type, val, meta){
                                return '<span type="text" readonly="readonly" class="date_of_purchase_of_the_expired_stock_removed" name="date_of_purchase_of_the_expired_stock_removed" />'+data+'</span>';
                            },
                            className: "date_of_purchase_of_the_expired_stock_removed",
                            searchable: false
                        },
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
                            data: 'value_of_removed_stock',
                            name: 'value_of_removed_stock',
                            'render': function (data, type, val, meta){
                                return '<span type="text" readonly="readonly" class="value_of_removed_stock" name="value_of_removed_stock" />'+data+'</span>';
                            },
                            className: "value_of_removed_stock",
                            searchable: false
                        },

                    ],
                    createdRow: function(row, data, dataIndex) {

                    },

                });

        });
        $(document).ready(function() {

            function calculateTotal(quentity) {
                $("#product_table tbody tr").each(function() {
                    var expired_current_stock = parseFloat($(this).find(".expired_current_stock").text());
                    var avg_purchase_price = parseFloat($(this).find(".avg_purchase_price").text());
                    var quantity_to_be_removed = parseFloat($(this).find(".quantity_to_be_removed").val());
                    var removedStockValue = quantity_to_be_removed * avg_purchase_price;
                    if (!isNaN(quantity_to_be_removed)) {
                        if (quantity_to_be_removed > expired_current_stock){
                            parseFloat($(this).find(".value_of_removed_stock").text("0.00"))
                            parseFloat($(this).find(".quantity_to_be_removed").val(0))
                        }else{
                            $(this).find(".value_of_removed_stock").text(removedStockValue.toFixed(2));
                        }
                    }
                });
            }


            $("#product_table").on("input", ".quantity_to_be_removed", function() {
                let quentity = $(this).val();

                calculateTotal(quentity);
            });
        });
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

        function sendData() {
            // Get the table instance
            var table = $('#product_table').DataTable();
            // Initialize an empty array to store the selected data
            var selectedData = [];
            var total_shortage_value = document.getElementById('total_shortage_value').value;
            // Loop through each row in the table
            table.rows().every(function() {
                var rowData = this.row().data();
                var quantity_to_be_removed = $('input[name="quantity_to_be_removed"]', this.node()).val();
                var expired_current_stock = $('span[name="expired_current_stock"]', this.node()).text();
                var date_of_purchase_of_the_expired_stock_removed = $('span[name="date_of_purchase_of_the_expired_stock_removed"]', this.node()).text();
                var value_of_removed_stock = $('.value_of_removed_stock', this.node()).text();
                var id = $('span[name="product_id"]', this.node()).text();
                var variation_id = $('span[name="variation_id"]', this.node()).text();

                // Check if actualStock has a value
                if (quantity_to_be_removed !== '') {
                    // Add the required data to the selectedData array
                    var dataObj = {
                        id: id,
                        variation_id : variation_id,
                        status : "expiry",
                        expired_current_stock: expired_current_stock,
                        quantity_to_be_removed: quantity_to_be_removed,
                        value_of_removed_stocks: value_of_removed_stock,
                        date_of_purchase_of_expired_stock_removed: date_of_purchase_of_the_expired_stock_removed,
                    };
                    selectedData.push(dataObj);
                }
            });
            // Send the data to the server
            $.ajax({
                type: 'POST',
                url: '/product/convolutions/storeStockRemoved',
                data: {
                    selected_data: selectedData,
                    total_shortage_value: total_shortage_value
                },
                success: function(response) {
                    swal(
                        'Success',
                        'Operation added successfully!',
                        'success'
                    );
                    location.reload();
                },
                error: function(xhr, status, error) {
                    swal(
                        'Error',
                        'Something went Error',
                        'error'
                    );
                }
            });
        }



    </script>
@endsection
