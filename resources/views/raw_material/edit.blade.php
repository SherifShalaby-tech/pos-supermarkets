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
                            @lang('lang.edit_raw_material')
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
                        'url' => action('RawMaterialController@update', $raw_material->id),
                        'id' => 'product-edit-form',
                        'method' => 'PUT',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-4 px-5">
                                    {!! Form::label('brand_id', __('lang.brand'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <div class="input-group my-group select-button-group">
                                        {!! Form::select('brand_id', $brands, !empty($raw_material) ? $raw_material->brand_id : false, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                        <span class="input-group-btn">
                                            @can('product_module.brand.create_and_edit')
                                                <button class="select-button btn-flat btn-modal"
                                                    data-href="{{ action('BrandController@create') }}?quick_add=1"
                                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                            @endcan
                                        </span>
                                    </div>
                                    <div class="error-msg text-red"></div>
                                </div>
                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        {!! Form::label('name', __('lang.name') . ' *', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('name', !empty($raw_material) ? $raw_material->name : null, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'required',
                                            'placeholder' => __('lang.name'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 px-5">
                                    <div class="form-group supplier_div">
                                        {!! Form::label('supplier_id', __('lang.supplier'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        <div class="input-group my-group select-button-group">
                                            {!! Form::select(
                                                'supplier_id',
                                                $suppliers,
                                                !empty($raw_material->supplier) ? $raw_material->supplier->id : false,
                                                [
                                                    'class' => 'selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                    'placeholder' => __('lang.please_select'),
                                                ],
                                            ) !!}
                                            <span class="input-group-btn">
                                                @can('supplier_module.supplier.create_and_edit')
                                                    <button type="button"class="select-button btn-flat btn-modal"
                                                        data-href="{{ action('SupplierController@create') }}?quick_add=1"
                                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                                @endcan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        {!! Form::label('sku', __('lang.sku'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('sku', !empty($raw_material) ? $raw_material->sku : null, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.sku'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 px-5">
                                    {!! Form::label('multiple_units', __('lang.unit'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <div class="input-group my-group select-button-group">
                                        {!! Form::select('multiple_units[]', $units, !empty($raw_material) ? $raw_material->multiple_units : false, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                            'id' => 'multiple_units',
                                        ]) !!}
                                        <span class="input-group-btn">
                                            @can('product_module.unit.create_and_edit')
                                                <button class="select-button btn-flat btn-modal"
                                                    data-href="{{ action('UnitController@create') }}?quick_add=1&is_raw_material_unit=1"
                                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                            @endcan
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    @if (!empty($raw_material->getFirstMediaUrl('product')))
                                        <div style="width: 120px;" class="images_div">
                                            <button type="button" class="delete-image btn btn-danger btn-xs"
                                                data-href="{{ action('ProductController@deleteProductImage', $raw_material->id) }}"
                                                style="margin-left: 100px; border-radius: 50%"><i
                                                    class="fa fa-times"></i></button>
                                            <img src="@if (!empty($raw_material->getFirstMediaUrl('product'))) {{ $raw_material->getFirstMediaUrl('product') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                                                alt="photo" style="width: 120px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 ">
                                    <div class="dropzone" id="my-dropzone">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#detailsCollapse" aria-expanded="false" aria-controls="detailsCollapse">
                            <i class="fas fa-arrow-down"></i>
                            @lang('lang.product_details')
                            <span class="toggle-pill"></span>
                        </button>
                    </div>


                    <div class="collapse" id="detailsCollapse">
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif">@lang('lang.product_details')</label>
                                        <textarea name="product_details" id="product_details"
                                            class="form-control modal-input  @if (app()->isLocale('ar')) text-end @else text-start @endif" rows="3">{{ !empty($raw_material) ? $raw_material->product_details : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 hide">
                        <div class="form-group">
                            {!! Form::label('barcode_type', __('lang.barcode_type'), []) !!}
                            {!! Form::select(
                                'barcode_type',
                                [
                                    'C128' => 'Code 128',
                                    'C39' => 'Code 39',
                                    'UPCA' => 'UPC-A',
                                    'UPCE' => 'UPC-E',
                                    'EAN8' => 'EAN-8',
                                    'EAN13' => 'EAN-13',
                                ],
                                !empty($raw_material) ? $raw_material->barcode_type : false,
                                ['class' => 'form-control', 'required'],
                            ) !!}
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="col-md-12">
                                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                    <div class="col-md-4 px-5">
                                        <div class="form-group">
                                            {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            {!! Form::text('alert_quantity', !empty($raw_material) ? @num_format($raw_material->alert_quantity) : 3, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                'placeholder' => __('lang.alert_quantity'),
                                            ]) !!}
                                        </div>
                                    </div>

                                    @can('product_module.purchase_price.create_and_edit')
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('purchase_price', __('lang.cost') . ' *', [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('purchase_price', !empty($raw_material) ? @num_format($raw_material->purchase_price) : null, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' =>
                                                        session('system_mode') == 'pos' ||
                                                        session('system_mode') == 'garments' ||
                                                        session('system_mode') == 'supermarket'
                                                            ? __('lang.purchase_price')
                                                            : __('lang.cost'),
                                                    'required',
                                                ]) !!}
                                            </div>
                                        </div>
                                    @endcan
                                    <div class="col-md-4">
                                        <label for=""
                                            class="unit_label">{{ $raw_material->alert_quantity_unit->name ?? '' }}</label>
                                    </div>
                                    <div class="col-md-4 px-5 hide">
                                        <div class="form-group">
                                            {!! Form::label('alert_quantity_unit_id', __('lang.unit'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            {!! Form::select(
                                                'alert_quantity_unit_id',
                                                $units,
                                                !empty($raw_material) ? $raw_material->alert_quantity_unit_id : false,
                                                [
                                                    'class' => 'selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'style' => 'width: 80%',
                                                    'placeholder' => __('lang.please_select'),
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 hide">
                        <table class="table table-bordered" id="consumption_table">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">@lang('lang.used_in')</th>
                                    <th style="width: 30%;">@lang('lang.used_amount')</th>
                                    <th style="width: 30%;">@lang('lang.unit')</th>
                                    <th style="width: 10%;"><button class="btn btn-xs btn-success add_product_row"
                                            type="button"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($raw_material->consumption_products as $consumption_product)
                                    @include('raw_material.partial.product_row', [
                                        'row_id' => $loop->index,
                                        'consumption_product' => $consumption_product,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="is_raw_material" id="is_raw_material" value="1">
                    <input type="hidden" name="row_id" id="row_id"
                        value="{{ $raw_material->consumption_products->count() }}">



                    <div class="col-md-12 hide">
                        <table class="table" id="variation_table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.sku')</th>
                                    <th>@lang('lang.color')</th>
                                    <th>@lang('lang.size')</th>
                                    <th>@lang('lang.grade')</th>
                                    <th>@lang('lang.unit')</th>
                                    <th>@lang('lang.purchase_price')</th>
                                    <th>@lang('lang.sell_price')</th>
                                    <th><button type="button" class="btn btn-success btn-xs add_row mt-2"><i
                                                class="dripicons-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($raw_material->variations as $item_v)
                                    @include('product.partial.edit_variation_row', [
                                        'row_id' => $loop->index,
                                        'item' => $item_v,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" name="active" value="1">

                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-4">
                            <input type="button" value="{{ trans('lang.submit') }}" id="submit-btn" class="btn py-1">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" id="product_cropper_modal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('lang.crop_image_before_upload')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img src="" id="product_sample_image" />
                            </div>
                            <div class="col-md-4">
                                <div class="product_preview_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="product_crop" class="btn btn-primary">@lang('lang.crop')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/product_edit.js') }}"></script>
    <script src="{{ asset('js/raw_material.js') }}"></script>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#detailsCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#detailsCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#detailsCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#detailsCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
    <script type="text/javascript"></script>
    <script>
        $("#submit-btn").on("click", function(e) {
            e.preventDefault();
            setTimeout(() => {
                if ($("#product-edit-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#product-edit-form").attr("action"),
                        data: $("#product-edit-form").serialize(),
                        success: function(response) {
                            if (response.success) {
                                swal("Success", response.msg, "success");
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
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
@endsection
