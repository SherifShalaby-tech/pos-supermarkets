@extends('layouts.app')
@section('title', __('lang.sales_promotion_formal_discount'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.edit_sales_promotion')</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open(['url' => action('SalesPromotionController@update', $sales_promotion->id), 'id' => 'customer-type-form', 'method' => 'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('name', __('lang.name') . ':*') !!}
                                        {!! Form::text('name', $sales_promotion->name, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('store_ids', __('lang.store') . ':*') !!}
                                        {!! Form::select('store_ids[]', $stores, $sales_promotion->store_ids, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'multiple', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('customer_type_ids', __('lang.customer_type') . ':*') !!}
                                        {!! Form::select('customer_type_ids[]', $customer_types, $sales_promotion->customer_type_ids, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'multiple', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('type', __('lang.type') . ':*') !!}
                                        {!! Form::select('type', ['item_discount' => __('lang.item_discount'), 'package_promotion' => __('lang.package_promotion')], $sales_promotion->type, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select'), 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-6">
                                        @include(
                                        'quotation.partial.product_selection'
                                    )
                                    </div>
                                </div>
                                <div class="col-md-6 product_condition_div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="i-checks" style="margin-top: 30px">
                                                    <input id="product_condition" name="product_condition"
                                                        @if ($sales_promotion->product_condition) checked @endif type="checkbox"
                                                        value="1" class="form-control-custom">
                                                    <label
                                                        for="product_condition"><strong>@lang('lang.product_condition')</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 @if (!$sales_promotion->product_condition) hide @endif" id="product_condition_selection">
                                            @include('quotation.partial.product_selection',['index'=>'_condition'])

                                        </div>
                                        <div class="col-md-12  ">
                                            <table class="table @if (!$sales_promotion->product_condition) hide @endif" id="sale_promotion_table_condition">
                                                <thead class="bg-success" style="color: white">
                                                <tr>
                                                    <th>@lang('lang.name')</th>
                                                    <th>@lang('lang.name')</th>
                                                    <th>@lang('lang.name')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @include('sales_promotion.partials.product_conditions_row',['products'=>$condition_products])
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 product_details_div mt-5 mb-5">
                                    <table class="table" id="sale_promotion_table">
                                        <thead class="bg-success" style="color: white">
                                            <tr>
                                                <th>@lang('lang.image')</th>
                                                <th>@lang('lang.name')</th>
                                                <th>@lang('lang.sku')</th>
                                                <th class="sum">@lang('lang.purchase_price')</th>
                                                <th class="sum">@lang('lang.sell_price')</th>
                                                <th>@lang('lang.stock')</th>
                                                <th>@lang('lang.expiry_date')</th>
                                                <th>@lang('lang.date_of_purchase')</th>
                                                <th class="qty_hide  @if ($sales_promotion->type == 'item_discount') hide @endif">
                                                    @lang('lang.qty')</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @include(
                                                'sales_promotion.partials.product_details_row',
                                                [
                                                    'products' => $product_details,
                                                    'type' => $sales_promotion->type,
                                                    'package_promotion_qty' =>
                                                        $sales_promotion->package_promotion_qty,
                                                ]
                                            )
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" style="text-align: right">@lang('lang.total')</th>
                                                <td class="footer_purchase_price_total"></td>
                                                <td class="footer_sell_price_total"></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        <input type="hidden" name="actual_sell_price" id="actual_sell_price"
                                            value="{{ $sales_promotion->actual_sell_price }}">
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="i-checks">
                                            <input id="purchase_condition" name="purchase_condition"
                                                @if ($sales_promotion->purchase_condition) checked @endif type="checkbox" value="1"
                                                class="form-control-custom">
                                            <label
                                                for="purchase_condition"><strong>@lang('lang.purchase_condition')</strong></label>
                                        </div>
                                        {!! Form::text('purchase_condition_amount', @num_format($sales_promotion->purchase_condition_amount), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('discount_type', __('lang.discount_type') . ':*') !!}
                                        {!! Form::select('discount_type', ['fixed' => 'Fixed', 'percentage' => 'Percentage'], $sales_promotion->discount_type, ['class' => 'form-control selecpicker', 'required', 'placeholder' => __('lang.please_select')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('discount_value', __('lang.discount') . ':*') !!}
                                        {!! Form::text('discount_value', @num_format($sales_promotion->discount_value), ['class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="" style="margin-top: 40px;"
                                        class="new_price @if ($sales_promotion->discount_type == 'package_promotion') hide @endif">@lang('lang.new_price'):
                                        <span class="new_price_span">{{ @num_format(0) }}</span></label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('start_date', __('lang.start_date') . ':') !!}
                                        {!! Form::text('start_date', $sales_promotion->start_date, ['class' => 'form-control datepicker']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('end_date', __('lang.end_date') . ':') !!}
                                        {!! Form::text('end_date', $sales_promotion->end_date, ['class' => 'form-control datepicker']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 mt-5">
                                    <div class="form-group">
                                        <div class="i-checks">
                                            <input id="generate_barcode" name="generate_barcode" type="checkbox" value="1"
                                                @if ($sales_promotion->generate_barcode == '1') checked @endif
                                                class="form-control-custom">
                                            <label
                                                for="generate_barcode"><strong>@lang('lang.generate_barcode')</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <input type="hidden" name="is_edit_page" id="is_edit_page" value="1">
                            <input type="hidden" name="sales_promotion_id" id="sales_promotion_id" value="{{$sales_promotion->id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" value="{{ trans('lang.submit') }}" id="submit-btn"
                                            class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        var product_selected = [];
        $(document).ready(function () {
            product_table = $("#product_selection_table").DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                deferLoading: 0,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [[2, "asc"]],
                ajax: {
                    url: "/product",
                    data: function (d) {
                        d.product_class_id = $("#filter_product_class_id").val();
                        d.category_id = $("#filter_category_id").val();
                        d.sub_category_id = $("#filter_sub_category_id").val();
                        d.brand_id = $("#filter_brand_id").val();
                        d.unit_id = $("#filter_unit_id").val();
                        d.color_id = $("#filter_color_id").val();
                        d.size_id = $("#filter_size_id").val();
                        d.grade_id = $("#filter_grade_id").val();
                        d.tax_id = $("#filter_tax_id").val();
                        if ($("#sender_store_id").length) {
                            //in add transfer page
                            d.store_id = $("#sender_store_id").val();
                        } else {
                            d.store_id = $("#filter_store_id").val();
                        }
                        d.customer_type_id = $("#filter_customer_type_id").val();
                        d.created_by = $("#filter_created_by").val();
                        d.is_raw_material = $("#is_raw_material").val();
                        d.is_add_stock = $("#is_add_stock").val();
                    },
                },
                columnDefs: [
                    {
                        targets: [0, 1],
                        orderable: false,
                        searchable: false,
                    },
                ],
                columns: [
                    {
                        data: "selection_checkbox",
                        name: "selection_checkbox",
                        searchable: false,
                        orderable: false,
                    },
                    { data: "image", name: "image" },
                    { data: "variation_name", name: "products.name" },
                    { data: "sub_sku", name: "variations.sub_sku" },
                    { data: "is_service", name: "products.is_service" },
                    { data: "product_class", name: "product_classes.name" },
                    { data: "category", name: "categories.name" },
                    { data: "sub_category", name: "categories.name" },
                    { data: "purchase_history", name: "purchase_history" },
                    { data: "batch_number", name: "add_stock_lines.batch_number" },
                    {
                        data: "default_sell_price",
                        name: "variations.default_sell_price",
                    },
                    { data: "tax", name: "taxes.name" },
                    { data: "brand", name: "brands.name" },
                    { data: "unit", name: "units.name" },
                    { data: "color", name: "colors.name" },
                    { data: "size", name: "sizes.name" },
                    { data: "grade", name: "grades.name" },
                    { data: "current_stock", name: "current_stock", searchable: false },
                    { data: "customer_type", name: "customer_type" },
                    { data: "exp_date", name: "add_stock_lines.expiry_date" },
                    {
                        data: "manufacturing_date",
                        name: "add_stock_lines.manufacturing_date",
                    },
                    { data: "discount", name: "discount" },
                    {
                        data: "default_purchase_price",
                        name: "default_purchase_price",
                        searchable: false,
                    },
                    { data: "supplier", name: "supplier" },
                    { data: "created_by", name: "users.name" },
                    { data: "edited_by_name", name: "edited.name" },
                    { data: "action", name: "action" },
                ],
                createdRow: function (row, data, dataIndex) {},
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($("#product_table"));
                },
            });
        });

        var hidden_column_array = $.cookie("column_visibility")
            ? JSON.parse($.cookie("column_visibility"))
            : [];
        $(document).ready(function () {
            $.each(hidden_column_array, function (index, value) {
                $(".column-toggle").each(function () {
                    if ($(this).val() == value) {
                        toggleColumnVisibility(value, $(this));
                    }
                });
            });
        });

        $(document).on("click", ".column-toggle", function () {
            let column_index = parseInt($(this).val());
            toggleColumnVisibility(column_index, $(this));
            if (hidden_column_array.includes(column_index)) {
                hidden_column_array.splice(
                    hidden_column_array.indexOf(column_index),
                    1
                );
            } else {
                hidden_column_array.push(column_index);
            }

            //unique array javascript
            hidden_column_array = $.grep(hidden_column_array, function (v, i) {
                return $.inArray(v, hidden_column_array) === i;
            });

            $.cookie("column_visibility", JSON.stringify(hidden_column_array));
        });

        function toggleColumnVisibility(column_index, this_btn) {
            column = product_table.column(column_index);
            column.visible(!column.visible());

            if (column.visible()) {
                $(this_btn).addClass("badge-primary");
                $(this_btn).removeClass("badge-warning");
            } else {
                $(this_btn).removeClass("badge-primary");
                $(this_btn).addClass("badge-warning");
            }
        }
        $(document).on("change", ".filter_product", function () {
            product_table.ajax.reload();
            product_table.ajax.reload();
        });

        $(document).on("click", ".clear_filters", function () {
            $(".filter_product").val("");
            $(".filter_product").selectpicker("refresh");
            product_table.ajax.reload();
        });
        $(document).on("change", ".product_selected", function () {
            let this_variation_id = $(this).val();
            let this_product_id = $(this).data("product_id");
            if ($(this).prop("checked")) {
                var obj = {};
                obj["product_id"] = this_product_id;
                obj["variation_id"] = this_variation_id;
                product_selected.push(obj);
            } else {
                product_selected = product_selected.filter(function (item) {
                    return (
                        item.product_id !== this_product_id &&
                        item.variation_id !== this_variation_id
                    );
                });
            }
            //remove duplicate object from array
            product_selected = product_selected.filter(
                (value, index, self) =>
                    index ===
                    self.findIndex(
                        (t) =>
                            t.product_id === value.product_id &&
                            t.variation_id === value.variation_id
                    )
            );
        });
        $("#select_products_modal").on("shown.bs.modal", function () {
            product_selected = [];
            product_table.ajax.reload();
        });
        //condition
        $(document).ready(function () {
            product_table_condition = $("#product_selection_table_condition").DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                deferLoading: 0,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [[2, "asc"]],
                ajax: {
                    url: "/product",
                    data: function (d) {
                        d.product_class_id = $("#filter_product_class_id_condition").val();
                        d.category_id = $("#filter_category_id_condition").val();
                        d.sub_category_id = $("#filter_sub_category_id_condition").val();
                        d.brand_id = $("#filter_brand_id_condition").val();
                        d.unit_id = $("#filter_unit_id_condition").val();
                        d.color_id = $("#filter_color_id_condition").val();
                        d.size_id = $("#filter_size_id_condition").val();
                        d.grade_id = $("#filter_grade_id_condition").val();
                        d.tax_id = $("#filter_tax_id_condition").val();
                        if ($("#sender_store_id_condition").length) {
                            //in add transfer page
                            d.store_id = $("#sender_store_id_condition").val();
                        } else {
                            d.store_id = $("#filter_store_id_condition").val();
                        }
                        d.customer_type_id = $("#filter_customer_type_id_condition").val();
                        d.created_by = $("#filter_created_by_condition").val();
                        d.is_raw_material = $("#is_raw_material").val();
                        d.is_add_stock = $("#is_add_stock").val();
                    },
                },
                columnDefs: [
                    {
                        targets: [0, 1],
                        orderable: false,
                        searchable: false,
                    },
                ],
                columns: [
                    {
                        data: "selection_checkbox",
                        name: "selection_checkbox",
                        searchable: false,
                        orderable: false,
                    },
                    { data: "image", name: "image" },
                    { data: "variation_name", name: "products.name" },
                    { data: "sub_sku", name: "variations.sub_sku" },
                    { data: "product_class", name: "product_classes.name" },
                    { data: "category", name: "categories.name" },
                    { data: "sub_category", name: "categories.name" },
                    { data: "purchase_history", name: "purchase_history" },
                    { data: "batch_number", name: "add_stock_lines.batch_number" },
                    {
                        data: "default_sell_price",
                        name: "variations.default_sell_price",
                    },
                    { data: "tax", name: "taxes.name" },
                    { data: "brand", name: "brands.name" },
                    { data: "unit", name: "units.name" },
                    { data: "color", name: "colors.name" },
                    { data: "size", name: "sizes.name" },
                    { data: "grade", name: "grades.name" },
                    { data: "current_stock", name: "current_stock", searchable: false },
                    { data: "customer_type", name: "customer_type" },
                    { data: "exp_date", name: "add_stock_lines.expiry_date" },
                    {
                        data: "manufacturing_date",
                        name: "add_stock_lines.manufacturing_date",
                    },
                    { data: "discount", name: "discount" },
                    {
                        data: "default_purchase_price",
                        name: "default_purchase_price",
                        searchable: false,
                    },
                    { data: "supplier", name: "supplier_name" },
                    { data: "created_by", name: "users.name" },
                    { data: "edited_by_name", name: "edited.name" },
                    { data: "action", name: "action" },
                ],
                createdRow: function (row, data, dataIndex) {},
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($("#product_table_condition"));
                },
            });
        });
        $(document).on("click", ".clear_filters_condition", function () {
            $(".filter_product_condition").val("");
            $(".filter_product_condition").selectpicker("refresh");
            product_table_condition.ajax.reload();
        });
        $(document).on("change", ".filter_product_condition", function () {
            product_table_condition.ajax.reload();
        });



        $("#select_products_modal_condition").on("shown.bs.modal", function () {

            product_selected = [];
            product_table_condition.ajax.reload();
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', '#product_condition', function() {
            if ($(this).is(':checked')) {
                $('#product_condition_selection').removeClass('hide');
                $('#sale_promotion_table_condition').removeClass('hide');
            } else {
                $('#product_condition_selection').addClass('hide');
                $('#sale_promotion_table_condition').addClass('hide');

            }
        });
        var is_edit_page = $("#is_edit_page").val();
        function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
        }
        function calculate_total_prices() {
            var total_purchase_price = 0;
            var total_sell_price = 0;
            $("#sale_promotion_table > tbody > tr").each((ele, tr) => {
                let purchase_price = __read_number($(tr).find(".purchase_price"));
                let sell_price = __read_number($(tr).find(".sell_price"));
                let qty = __read_number($(tr).find(".qty"));
                total_purchase_price += purchase_price * qty;
                total_sell_price += sell_price * qty;
            });
            $(".footer_purchase_price_total").text(
                __currency_trans_from_en(total_purchase_price, false)
            );
            $(".footer_sell_price_total").text(
                __currency_trans_from_en(total_sell_price, false)
            );
            $('#actual_sell_price').val(total_sell_price);
        }
        function getProductRows(array) {
            $(".footer_sell_price_total").text(__currency_trans_from_en(0, false));
            $(".footer_purchase_price_total").text(__currency_trans_from_en(0, false));
            $.ajax({
                method: "get",
                url: "/sales-promotion/get-product-details-rows",
                data: {
                    store_ids: $("#store_ids").val(),
                    type: $("#type").val(),
                    array: array,
                    is_edit: is_edit_page,
                },
                dataType: "html",
                success: function(result) {
                    //  callback function
                    handleResponse(result);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //  callback function
                    handleError(errorThrown);
                }
            });

            function handleResponse(response) {
                $("#sale_promotion_table tbody").append(response);
                let sell_price_total = 0;
                let purchase_price_total = 0;
                if ($("#sell_price_total").length > 0) {
                    sell_price_total = $("#sell_price_total").val();
                }
                if ($("#purchase_price_total").length > 0) {
                    purchase_price_total = $("#purchase_price_total").val();
                }

                $(".footer_sell_price_total").text(
                    __currency_trans_from_en(sell_price_total, false)
                );
                $(".footer_purchase_price_total").text(
                    __currency_trans_from_en(purchase_price_total, false)
                );
                calculate_total_prices();
            }

            function handleError(error) {
            }

        }

        function getProductRowsCondition(array) {

            $.ajax({
                method: "get",
                url: "/sales-promotion/get-product-condition-rows",
                data: {
                    store_ids: $("#store_ids").val(),
                    type: $("#type").val(),
                    array: array,
                    is_edit: is_edit_page,
                },
                dataType: "html",
                success: function(result) {
                    //  callback function
                    handleResponse(result);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //  callback function
                    handleError(errorThrown);
                }
            });

            function handleResponse(response) {
                $("#sale_promotion_table_condition tbody").html(response);
            }

            function handleError(error) {
            }

        }
        $(document).on('click', '#add-selected-btn', function() {
            product_array = [];
            $('#select_products_modal').modal('hide');
            product_variations_ids=[];
            $(".product_variations_ids").each(function () {
                product_variations_ids.push($(this).val());
            });
            $.each(product_selected, function(index, value) {
                if ($.inArray(value.variation_id, product_variations_ids) == -1){
                    product_array.push(value.variation_id);
                }

            });
            unique_product_array = [];
            unique_product_array = product_array.filter(onlyUnique);
            getProductRows(unique_product_array);
            // product_table.ajax.reload();
        });
        $(document).on('click', '#add-selected-btn_condition', function() {
            product_array = [];
            $('#select_products_modal_condition').modal('hide');
            product_variations_ids=[];

            $.each(product_selected, function(index, value) {
                if ($.inArray(value.variation_id, product_variations_ids) == -1){
                    product_array.push(value.variation_id);
                }
            });
            unique_product_array = [];
            unique_product_array = product_array.filter(onlyUnique);
            getProductRowsCondition(unique_product_array);
            // product_table.ajax.reload();
        });
        $(document).on("click", ".remove_row_sp", function () {
            const product_id = parseInt($(this).data("product_id"));
            $(this).closest("tr").remove();
            $(".product_checkbox").filter(function () {
                return this.checked && parseInt(this.value) === product_id;
            }).each(function () {
                const index = unique_product_array.indexOf(product_id);
                unique_product_array.splice(index, 1);
                this.checked = false;
            });
            calculate_total_prices();
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#discount_type').change();
            $('#type').change();
            calculate_total_prices();
        })
        $('.selectpicker').selectpicker('selectAll');

        $(document).on('change', '#type', function() {

            if ($(this).val() === 'package_promotion') {
                $(".product_condition_div").addClass("hide");
                $(".qty_hide").removeClass("hide");
                console.log( $('.new_price').removeClass('hide'));
            } else {
                $(".product_condition_div").removeClass("hide");
                $(".qty_hide").addClass("hide");
                $('.new_price').addClass('hide');
                $('.qty').val(1);
            }
        })
        $(document).on('change', '#discount_type, #discount_value', function() {
            let type = $('#type').val()
            let discount_type = $('#discount_type').val();
            let discount_value = __read_number($('#discount_value'))

            let new_price = 0;
            if (type == 'package_promotion') {
                if (discount_type == 'fixed') {
                    new_price = discount_value;
                }
                if (discount_type == 'percentage') {
                    let actual_sell_price = __read_number($('#actual_sell_price'))
                    new_price = (actual_sell_price * discount_value) / 100;
                }
            }
            $('.new_price_span').text(__currency_trans_from_en(new_price, false))

        })
    </script>

@endsection
