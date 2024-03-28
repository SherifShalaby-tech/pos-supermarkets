@extends('layouts.app')
@section('title', __('lang.raw_materials'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('content')

    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.edit_manufacturing_status')</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open([
                                'url' => action('ManufacturingController@updates'),
                                'id' => 'product-edit-form',
                                'method' => 'POST',
                                'class' => '',
                                'enctype' => 'multipart/form-data',
                            ]) !!}
                            <input type="hidden" name="store_id" value="{{ $manufacturing->store->id }}">
                            <input type="hidden" name="manufacturer_id" value="{{ $manufacturing->manufacturer->id }}">
                            <input type="hidden" name="manufacturing_id" value="{{ $manufacturing->id }}">
                            <input type="hidden" id="product_ids" name="product_ids"
                                value="{{ json_encode($product_ids) }}">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('store_id', __('lang.store'), []) !!}
                                    <div class="input-group my-group">
                                        <select required name="store_id" id="store_id"
                                            class='select_product_ids selectpicker  form-control' data-live-search='true'
                                            style='width: 30%;' placeholder="{{ __('lang.please_select') }}">

                                            <option selected disabled value="{{ $manufacturing->store->id }}">
                                                {{ $manufacturing->store->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('manufacturer_id', __('lang.manufacturer'), []) !!}
                                    <div class="input-group my-group">
                                        <select required name="manufacturer_id" id="manufacturer_id"
                                            class='select_product_ids selectpicker  form-control' data-live-search='true'
                                            style='width: 30%;' placeholder="{{ __('lang.please_select') }}">
                                            <option selected disabled value="{{ $manufacturing->manufacturer->id }}">
                                                {{ $manufacturing->manufacturer->name }}
                                            </option>
                                        </select>

                                    </div>
                                </div>


                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                                        <thead>
                                            <tr>

                                                <th style="width: 7%" class="col-sm-8">@lang('lang.image')</th>
                                                <th style="width: 10%" class="col-sm-8">@lang('lang.products')</th>
                                                <th style="width: 10%" class="col-sm-8">@lang('lang.status')</th>
                                                <th style="width: 5%" class="col-sm-4">@lang('lang.quantity')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.unit')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($underManufacturings) && count($underManufacturings) > 0)
                                                @foreach ($underManufacturings as $material)
                                                    <tr>
                                                        <td>
                                                            <img src="@if (!empty($material->product->getFirstMediaUrl('product'))) {{ $material->product->getFirstMediaUrl('product') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                                                                alt="photo" width="50" height="50">
                                                        </td>
                                                        </td>
                                                        <td>
                                                            {{ $material->product->name }}
                                                        </td>
                                                        <td>
                                                            {{ $material->status == '1' ? __('lang.manufactured') : __('lang.underManufacturing') }}
                                                        </td>
                                                        <input type="hidden"
                                                            id="product_stock_{{ $material->product->id }}"
                                                            value="{{ $material->product->product_stores->first()->qty_available }} ">
                                                        <td>
                                                            <input type="hidden"
                                                                class="form-control quantity product_{{ $material->product->id }}"
                                                                id="input_product_status_{{ $material->product->id }}"
                                                                name="product_material_under_manufactured[{{ $material->product->id }}][status]"
                                                                required value="{{ $material->status }}">
                                                            <input type="text"
                                                                class="form-control quantity product_{{ $material->product->id }}"
                                                                id="input_product_{{ $material->product->id }}"
                                                                name="product_material_under_manufactured[{{ $material->product->id }}][quantity]"
                                                                required value="{{ $material->quantity }}">
                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>
                                                            <button style="margin-top: 33px;" type="button"
                                                                class="btn btn-danger btn-sx remove_product_row"><i
                                                                    class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            @if (isset($manufactureds) && count($manufactureds) > 0)
                                                @foreach ($manufactureds as $material)
                                                    <tr>
                                                        <td>
                                                            <img src="@if (!empty($material->product->getFirstMediaUrl('product'))) {{ $material->product->getFirstMediaUrl('product') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                                                                alt="photo" width="50" height="50">
                                                        </td>
                                                        </td>
                                                        <td>
                                                            {{ $material->product->name }}
                                                        </td>
                                                        <td>
                                                            {{ $material->status == '1' ? __('lang.manufactured') : __('lang.underManufacturing') }}
                                                        </td>
                                                        <input type="hidden"
                                                            id="product_stock_{{ $material->product->id }}"
                                                            value="{{ $material->product->product_stores->first()->qty_available }} ">
                                                        <td>
                                                            <input type="hidden"
                                                                class="form-control quantity product_{{ $material->product->id }}"
                                                                id="input_product_status_{{ $material->product->id }}"
                                                                name="product_material_recived[{{ $material->product->id }}][status]"
                                                                required value="{{ $material->status }}">
                                                            <input type="text"
                                                                class="form-control quantity product_{{ $material->product->id }}"
                                                                id="input_product_{{ $material->product->id }}"
                                                                name="product_material_recived[{{ $material->product->id }}][quantity]"
                                                                required value="{{ $material->quantity }}">
                                                        </td>
                                                        <td>
                                                        </td>
                                                        <td>
                                                            <button style="margin-top: 33px;" type="button"
                                                                class="btn btn-danger btn-sx remove_product_row"><i
                                                                    class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <input type="hidden" name="active" value="1">
                            <div class="row">
                                <div class="col-md-4 mt-5">
                                    <div class="form-group">
                                        <input type="button" value="{{ trans('lang.edit') }}" id="submit-btns"
                                            class="btn btn-primary mr-3">
                                        <a href="{{ route('manufacturing-s.index') }}"
                                            class="btn btn-danger">{{ trans('lang.cancel') }}</a>
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

@push('javascripts')
    <script src="{{ asset('js/add_stock.js') }}"></script>
    <script src="{{ asset('js/product_selection_manufacturing.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            let product_ids = JSON.parse($("#product_ids").val());
            for (var key in product_ids) {
                if (product_ids.hasOwnProperty(key)) {
                    console.log($("#input_product_" + key).val())
                    let product_id = key;
                    let quentity = Number(product_ids[key]);
                    let stock = Number($("#product_stock_" + product_id).val())
                    let status = $("#input_product_" + product_id).val()
                    $("#input_product_" + product_id).on('keyup', function(e) {
                        setTimeout(g => {
                            if (Number($("#input_product_" + product_id).val()) == 0) {
                                $("#input_product_" + product_id).val(1)
                                swal("Error", "Sorry You Should enter quentity more than 0",
                                    "error");
                            }
                        }, 5000)
                        if (Number($("#input_product_" + product_id).val()) > (quentity + stock)) {
                            $("#input_product_" + product_id).val(1)
                            swal("Error", "Sorry Out Of Stock", "error");
                        }
                    })
                }
            }


        })


        $(document).on("click", ".remove_product_row", function() {

        })


        function get_label_product_row(product_id, variation_id) {
            var add_via_ajax = true;
            var store_id = $("#store_id").val();
            var is_added = false;
            let q = 0;
            //Search for variation id in each row of pos table
            $("#product_table tbody")
                .find("tr")
                .each(function() {
                    var row_v_id = $(this).find(".variation_id").val();
                    if (row_v_id == variation_id && !is_added) {
                        add_via_ajax = false;
                        is_added = true;
                        //Increment product quantity
                        qty_element = $(this).find(".quantity");
                        var qty = __read_number(qty_element);
                        __write_number(qty_element, qty + 1);
                        qty_element.change;
                        calculate_sub_totals();
                        $("input#search_product").val("");
                        $("input#search_product").focus();
                    }
                });

            if (add_via_ajax) {
                var row_count = parseInt($("#row_count").val());
                let currency_id = $('#paying_currency_id').val()
                $("#row_count").val(row_count + 1);
                $.ajax({
                    method: "GET",
                    url: "/manufacturing/add-product-to-stock",
                    dataType: "html",
                    async: false,
                    data: {
                        product_id: product_id,
                        row_count: row_count,
                        variation_id: variation_id,
                        store_id: store_id,
                        currency_id: currency_id,
                    },
                    success: function(result) {
                        $('.items_quantity_count').text(0);
                        $("table#product_table tbody").prepend(result);

                        let product_id = $("#product_id").val();


                        let items_quantity_count = $('#product_table tbody tr').length;
                        $('.items_quantity_count').text(items_quantity_count);


                        setTimeout(t => {
                            for (i = 0; i < $('#product_table tbody tr').length; i++) {
                                q += Number($($(".product_row")[i].children[2].children[1]).val())
                            }
                            $('.items_product_count').text(q);
                        }, 100)


                        $("input#search_product").val("");
                        $("input#search_product").focus();
                        calculate_sub_totals();
                        reset_row_numbering();
                    },
                });
            }
        }

        $(document).on("click", ".remove_product_row", function() {
            let index = $(this).data("index");
            let q = 0;
            $(this).closest("tr").remove();
            $(".row_details_" + index).remove();
            $(".bounce_details_td_" + index).remove();


            for (i = 0; i < $('#product_table tbody tr').length; i++) {
                q += Number($($(".product_row")[i].children[2].children[1]).val())
            }
            $('.items_product_count').text(q);

            let items_quantity_count = $('#product_table tbody tr').length;
            $('.items_quantity_count').text(items_quantity_count);


            calculate_sub_totals();
            reset_row_numbering();
        });
        $(document).on('click', '#add-selected-btn', function() {
            $('#select_products_modal').modal('hide');
            $.each(product_selected, function(index, value) {
                get_label_product_row(value.product_id, value.variation_id);
            });
            product_selected = [];
            product_table.ajax.reload();
        })
        @if (!empty($product_id) && !empty($variation_id))
            $(document).ready(function() {
                $('.items_quantity_count').text(0);
                get_label_product_row({{ $product_id }}, {{ $variation_id }});

            })
        @endif
        $('#po_no').change(function() {
            let po_no = $(this).val();

            if (po_no) {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-purchase-order-details/' + po_no,
                    data: {},
                    contentType: 'html',
                    success: function(result) {
                        $("table#product_table tbody").empty().append(result);
                        calculate_sub_totals()
                    },
                });
            }
        });
        $(document).on("click", '#submit-btn-add-product', function(e) {
            e.preventDefault();
            var sku = $('#sku').val();
            if ($("#product-form-quick-add").valid()) {
                tinyMCE.triggerSave();
                $.ajax({
                    type: "POST",
                    url: "/product",
                    data: $("#product-form-quick-add").serialize(),
                    success: function(response) {
                        if (response.success) {
                            swal("Success", response.msg, "success");
                            $("#search_product").val(sku);
                            $('input#search_product').autocomplete("search");
                            $('.view_modal').modal('hide');
                        }
                    },
                    error: function(response) {
                        if (!response.success) {
                            swal("Error", response.msg, "error");
                        }
                    },
                });
            }
        });
        $(document).on("change", "#category_id", function() {
            $.ajax({
                method: "get",
                url: "/category/get-sub-category-dropdown?category_id=" +
                    $("#category_id").val(),
                data: {},
                contentType: "html",
                success: function(result) {
                    $("#sub_category_id").empty().append(result).change();
                    $("#sub_category_id").selectpicker("refresh");

                    if (sub_category_id) {
                        $("#sub_category_id").selectpicker("val", sub_category_id);
                    }
                },
            });
        });
        $('#payment_status').change(function() {
            var payment_status = $(this).val();

            if (payment_status === 'paid' || payment_status === 'partial') {
                $('.not_cash_fields').addClass('hide');
                $('#method').change();
                $('#method').attr('required', true);
                $('#paid_on').attr('required', true);
                $('.payment_fields').removeClass('hide');
            } else {
                $('.payment_fields').addClass('hide');
            }
            if (payment_status === 'pending' || payment_status === 'partial') {
                $('.due_fields').removeClass('hide');
            } else {
                $('.due_fields').addClass('hide');
            }
            if (payment_status === 'pending') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
                $('#method').attr('required', false);
                $('#paid_on').attr('required', false);
            } else {
                $('#method').attr('required', true);
            }
            if (payment_status === 'paid') {
                $('.due_fields').addClass('hide');
            }
        })
        $('#method').change(function() {
            var method = $(this).val();

            if (method === 'cash') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
            } else {
                $('.not_cash_fields').removeClass('hide');
                $('.not_cash').attr('required', true);
            }
        });

        $(document).ready(function() {
            $('#payment_status').change();
            $('#source_type').change();
        })
        $('#source_type').change(function() {
            if ($(this).val() !== '') {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-source-by-type-dropdown/' + $(this).val(),
                    data: {},
                    success: function(result) {
                        $("#source_id").empty().append(result);
                        $("#source_id").selectpicker("refresh");
                    },
                });
            }
        });

        $(document).on('change', '.expiry_date', function() {
            if ($(this).val() != '') {
                let tr = $(this).parents('tr');
                tr.find('.days_before_the_expiry_date').val(15);
            }
        })


        $(document).ready(function() {
            $("#submit-btns").on("click", function(e) {
                e.preventDefault();
                if ($('#product_table tbody tr').length > 0) {
                    $.ajax({
                        type: "POST",
                        url: $("#product-edit-form").attr("action"),
                        data: $("#product-edit-form").serialize(),
                        success: function(response) {
                            if (response.success) {
                                swal("Success", response.msg, "success")
                                setTimeout(t => {
                                    window.location.href =
                                        "{{ action('ManufacturingController@index') }}";
                                }, 2000)

                            }
                            if (!response.success) {
                                swal("Error", response.msg, "error");
                            }
                        },
                        error: function(response) {
                            if (!response.success) {
                                swal("Error", response.msg, "error");
                            }
                        },
                    });
                } else {
                    swal("Error", "Sorry You Should Select Returned Materials", "error");
                }

            });
        });
    </script>
@endpush
