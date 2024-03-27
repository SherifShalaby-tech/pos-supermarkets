@extends('layouts.app')
@section('title', __('lang.raw_materials'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('content')

    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div
                        class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <h5 class="mb-0 position-relative" style="margin-right: 30px">
                            @lang('lang.add_new_production')
                            <span class="header-pill"></span>
                        </h5>
                    </div>
                    <div class="card mb-2 d-flex flex-row justify-content-center align-items-center">
                        <p class="italic mb-0 py-1">
                            <small>@lang('lang.required_fields_info')</small>
                        <div style="width: 30px;height: 30px;">
                            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
                        </div>
                        </p>
                    </div>
                    {!! Form::open([
                        'url' => action('ManufacturingController@store'),
                        'id' => 'product-edit-form',
                        'method' => 'POST',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            {{-- <input type="hidden" name="is_add_stock" id="is_add_stock" value="1"> --}}
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-3  px-5">
                                    {!! Form::label('store_id', __('lang.store'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <div class="input-group my-group">
                                        {!! Form::select('store_id', $stores, false, [
                                            'class' => 'select_store_id selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'id' => 'store_id',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    {!! Form::label('manufacturer_id', __('lang.manufacturers'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <div class="input-group my-group">
                                        {!! Form::select('manufacturer_id', $manufacturers, false, [
                                            'class' => 'select_manufacturer_id selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'id' => 'manufacturer_id',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-3 px-5">
                                    {!! Form::label('product_ids', __('lang.manufacturing_material'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <div class="input-group my-group">
                                        @include('quotation.partial.product_selection')
                                        {{--                                        <select required name="product_ids[]" id="product_ids" multiple --}}
                                        {{--                                                class='select_product_ids selectpicker select2 form-control' --}}
                                        {{--                                                data-live-search='true' style='width: 30%;' --}}
                                        {{--                                                placeholder="{{__('lang.please_select')}}"> --}}
                                        {{--                                            @foreach ($products as $item) --}}
                                        {{--                                                @if ($item->current_stock > 0) --}}
                                        {{--                                                    <option stock="{{ $item->current_stock }}" name="{{$item->name}}" --}}
                                        {{--                                                            id="product_{{$item->id}}" value="{{$item->id}}"> --}}
                                        {{--                                                        {{ $item->name }} --}}
                                        {{--                                                    </option> --}}
                                        {{--                                                @endif --}}
                                        {{--                                            @endforeach --}}
                                        {{--                                        </select> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                                        <thead>
                                            <tr>

                                                <th style="width: 7%" class="col-sm-8">@lang('lang.image')</th>
                                                <th style="width: 10%" class="col-sm-8">@lang('lang.products')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.current_stock')</th>
                                                <th style="width: 5%" class="col-sm-4">@lang('lang.quantity')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.unit')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="w-75 m-auto d-flex justify-content-between">
                                <h4
                                    class="count_wrapper col-md-6 d-flex @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                    <span class="count_title col-md-6">@lang('lang.items_count')</span>
                                    <span class="count_value_style col-md-6 items_quantity_count">0</span>
                                </h4>
                                <h4
                                    class="count_wrapper col-md-6 d-flex @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                    <span class="count_title col-md-6">@lang('lang.items_quantity')</span>
                                    <span class="count_value_style col-md-6 items_product_count">0</span>
                                </h4>
                            </div>

                            <input type="hidden" name="active" value="1">
                            <div class="row my-2 justify-content-center align-items-center">
                                <div class="col-md-4 w-25">
                                    <input type="button" value="{{ trans('lang.manufacturing') }}" id="submit-btn"
                                        class="btn btn-primary py-1 w-100 pull-right btn-flat">
                                </div>
                                <div class="col-md-4 w-25">
                                    <a href="{{ route('manufacturing-s.index') }}"
                                        class="btn btn-dark py-1 w-100 pull-right btn-flat">{{ trans('lang.cancel') }}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
    {{--    <div class="modal fade" id="ProductsModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"> --}}
    {{--        <div class="modal-dialog" role="document"> --}}
    {{--            <div class="modal-content"> --}}
    {{--                <div class="modal-header"> --}}
    {{--                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5> --}}
    {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> --}}
    {{--                        <span aria-hidden="true">&times;</span> --}}
    {{--                    </button> --}}
    {{--                </div> --}}
    {{--                <div class="modal-body"> --}}
    {{--                    <table id="product-modal" class="table table-striped table-bordered"> --}}
    {{--                        <thead> --}}
    {{--                            <th>@lang('lang.name')</th> --}}
    {{--                            <th>@lang('lang.stock')</th> --}}
    {{--                            <th>@lang('lang.quantity')</th> --}}
    {{--                            <th>@lang('lang.unit')</th> --}}
    {{--                        </thead> --}}
    {{--                        <tbody> --}}

    {{--                        </tbody> --}}
    {{--                    </table> --}}
    {{--                </div> --}}
    {{--                <div class="modal-footer"> --}}
    {{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
    {{--                    <button type="button" id="addQuantity" class="btn btn-primary">Understood</button> --}}
    {{--                </div> --}}
    {{--            </div> --}}
    {{--        </div> --}}
    {{--    </div> --}}
@endsection

@push('javascripts')
    <script src="{{ asset('js/add_stock.js') }}"></script>
    <script src="{{ asset('js/product_selection_manufacturing.js') }}"></script>
    <script type="text/javascript">
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
                    url: "/manufacturing/add-product-row",
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

                        $("#input_product_" + product_id).on('keyup', function(e) {
                            if (Number($("#input_product_" + product_id).val()) == 0) {
                                $("#input_product_" + product_id).val(1)
                                swal("Error", "Sorry You Should enter quentity more than 0", "error");
                            }
                            if (Number($("#input_product_" + product_id).val()) > Number(document
                                    .getElementById("product_stock_" + product_id).innerHTML)) {
                                $("#input_product_" + product_id).val(1)
                                swal("Error", "Sorry Out Of Stock", "error");
                            }
                            let q = 0;
                            for (i = 0; i < $('#product_table tbody tr').length; i++) {
                                q += Number($($(".product_row")[i].children[3].children[1]).val())
                            }
                            $('.items_product_count').text(q);
                        })
                        let items_quantity_count = $('#product_table tbody tr').length;
                        $('.items_quantity_count').text(items_quantity_count);


                        setTimeout(t => {
                            for (i = 0; i < $('#product_table tbody tr').length; i++) {
                                q += Number($($(".product_row")[i].children[3].children[1]).val())
                            }
                            $('.items_product_count').text(q);
                        }, 1000)



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
                q += Number($($(".product_row")[i].children[3].children[1]).val())
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
            $("#submit-btn").on("click", function(e) {
                e.preventDefault();
                if ($("#product-edit-form").valid()) {
                    $.ajax({
                        type: "POST",
                        url: $("#product-edit-form").attr("action"),
                        data: $("#product-edit-form").serialize(),
                        success: function(response) {
                            if (response.success) {
                                swal("Success", response.msg, "success")
                                setTimeout(t => {
                                    window.history.back()
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

                }
            });
        });
    </script>











    {{--     select multi js --}}

    {{--        let data = []; --}}
    {{--        let Quantities = []; --}}
    {{--        $(document).ready(function () { --}}
    {{--            $("#submit-btn").on("click", function (e) { --}}
    {{--                e.preventDefault(); --}}
    {{--                if ($("#product-edit-form").valid()) { --}}
    {{--                    $.ajax({ --}}
    {{--                        type: "POST", --}}
    {{--                        url: $("#product-edit-form").attr("action"), --}}
    {{--                        data: { --}}
    {{--                            "store_id" :$("#store_id").val(), --}}
    {{--                            "manufacturer_id" :$("#manufacturer_id").val(), --}}
    {{--                            "product_quentity" :Quantities, --}}
    {{--                        }, --}}
    {{--                        success: function (response) { --}}
    {{--                            if (response.success) { --}}
    {{--                                swal("Success", response.msg, "success") --}}
    {{--                            } --}}
    {{--                            if (!response.success) { --}}
    {{--                                swal("Error", response.msg, "error"); --}}
    {{--                            } --}}
    {{--                        }, --}}
    {{--                        error: function (response) { --}}
    {{--                            if (!response.success) { --}}
    {{--                                swal("Error", response.msg, "error"); --}}
    {{--                            } --}}
    {{--                        }, --}}
    {{--                    }); --}}

    {{--                } --}}
    {{--            }); --}}
    {{--            }); --}}

    {{--            $(document).on("change", "#product_ids", function (e) { --}}

    {{--                if(data.length < $(this).val().length){ --}}
    {{--                    let product_current; --}}
    {{--                    $(this).val().forEach(e=>{ --}}
    {{--                        if (data.filter(function(g) { return g.id == e; }).length == 0) { --}}
    {{--                            product_current =e; --}}
    {{--                        } --}}
    {{--                    }) --}}
    {{--                    // select added new value --}}
    {{--                    $("#product-modal tbody").empty() --}}
    {{--                    $("#product-modal tbody").append(` --}}
    {{--                <tr> --}}
    {{--                        <td> --}}
    {{--                            ${data.length == 0 ? $('#product_'+$(this).val()[0]).attr('name'): $('#product_'+product_current).attr('name')} --}}
    {{--                        </td> --}}
    {{--                        <td> --}}
    {{--                              ${data.length == 0 ? $('#product_'+$(this).val()[0]).attr('stock'): $('#product_'+product_current).attr('stock')} --}}
    {{--                        </td> --}}
    {{--                        <td> --}}
    {{--                            <input class="form-control" type="number" id="product_quantity"> --}}
    {{--                            <input type="hidden" id="product_stock" value=" ${data.length == 0 ? $('#product_'+$(this).val()[0]).attr('stock'): $('#product_'+product_current).attr('stock')}"> --}}
    {{--                            <input type="hidden" id="product_id" value=" ${data.length == 0 ? $('#product_'+$(this).val()[0]).val(): $('#product_'+product_current).val()}"> --}}
    {{--                        </td> --}}
    {{--                        <td> --}}
    {{--                            GM --}}
    {{--                        </td> --}}
    {{--                    </tr> --}}
    {{--                `) --}}
    {{--                    $("#ProductsModal").modal("show") --}}
    {{--                }else{ --}}
    {{--                    // select  remove old  value --}}

    {{--                    console.log("$(this).val()",$(this).val()) --}}
    {{--                    if($(this).val().length == 0){ --}}
    {{--                        Quantities =[]; --}}
    {{--                    }else{ --}}
    {{--                        Quantities.forEach(a=>{ --}}
    {{--                            if ($(this).val().filter(function(g) { return g == a.product_id; }).length == 0) { --}}
    {{--                                const index = Quantities.indexOf(a); --}}
    {{--                                if (index > -1) { --}}
    {{--                                    Quantities.splice(index, 1); --}}
    {{--                                } --}}
    {{--                            } --}}
    {{--                        }) --}}
    {{--                    } --}}

    {{--                    console.log(Quantities) --}}

    {{--                } --}}

    {{--                data = []; --}}
    {{--                product_ids = $(this).val(); --}}
    {{--                product_ids.forEach(e => { --}}
    {{--                    data.push({ --}}
    {{--                        "id": e, --}}
    {{--                        "name": $('#product_' + e).attr('name'), --}}
    {{--                        "stock": $('#product_' + e).attr('stock') --}}
    {{--                    }) --}}
    {{--                }); --}}

    {{--            }); --}}
    {{--            $(document).on("click", "#addQuantity", function (e) { --}}
    {{--                let userQuantity= Number($("#product_quantity").val()); --}}
    {{--                let product_stock= Number($("#product_stock").val()); --}}
    {{--                let product_id= Number($("#product_id").val()); --}}
    {{--                if(userQuantity == null || userQuantity==''){ --}}
    {{--                    swal("Error", "Please Fill Quantity Input", "error"); --}}
    {{--                }else if(userQuantity > product_stock){ --}}
    {{--                    swal("Error", "Sorry Out Of Stock", "error"); --}}
    {{--                }else{ --}}
    {{--                    Quantities.push({ --}}
    {{--                        "product_id" :product_id, --}}
    {{--                        "quantity" :userQuantity, --}}
    {{--                    }) --}}
    {{--                    // console.log(Quantities) --}}
    {{--                    swal("Success", 'Quantity Added Successfully', "success"); --}}
    {{--                    $("#ProductsModal").modal("hide") --}}
    {{--                } --}}
    {{--            }); --}}
@endpush
