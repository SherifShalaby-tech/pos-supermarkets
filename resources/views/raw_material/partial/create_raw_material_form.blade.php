@php
    $recent_product = App\Models\Product::where('is_raw_material', 1)->orderBy('created_at', 'desc')->first();
@endphp
<div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
    <div class="col-md-4 px-5">
        {!! Form::label('brand_id', __('lang.brand'), [
            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
        ]) !!}
        <div class="input-group my-group select-button-group">
            {!! Form::select('brand_id', $brands, !empty($recent_product) ? $recent_product->brand_id : false, [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'style' => 'width: 80%',
                'placeholder' => __('lang.please_select'),
            ]) !!}
            <span class="input-group-btn">
                @can('product_module.brand.create_and_edit')
                    <button class="select-button btn-flat btn-modal"
                        data-href="{{ action('BrandController@create') }}?quick_add=1" data-container=".view_modal"><i
                            class="fa fa-plus"></i></button>
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
            {!! Form::text('name', !empty($recent_product) ? $recent_product->name : null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
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
                    !empty($recent_product->supplier) ? $recent_product->supplier->id : false,
                    [
                        'class' => 'selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'placeholder' => __('lang.please_select'),
                    ],
                ) !!}
                <span class="input-group-btn">
                    @can('supplier_module.supplier.create_and_edit')
                        <button type="button" class="select-button btn-flat btn-modal"
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
            {!! Form::text('sku', null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.sku'),
            ]) !!}
        </div>
    </div>

    <div class="col-md-4 px-5">
        {!! Form::label('multiple_units', __('lang.unit'), [
            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
        ]) !!}
        <div class="input-group my-group select-button-group">
            {!! Form::select('multiple_units[]', $units, false, [
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

    <div class="col-md-12 ">
        <div class="dropzone" id="my-dropzone">
        </div>
    </div>
</div>

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#detailsCollapse" aria-expanded="false" aria-controls="detailsCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.product_details')
        <span class="toggle-pill"></span>
    </button>
</div>

<div class="collapse" id="detailsCollapse">

    <div class="col-md-12">
        <div class="form-group">
            <label
                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif">@lang('lang.product_details')</label>
            <textarea name="product_details" id="product_details"
                class="form-control modal-input  @if (app()->isLocale('ar')) text-end @else text-start @endif" rows="3">{{ !empty($recent_product) ? $recent_product->product_details : '' }}</textarea>
        </div>
    </div>
</div>

<div class="col-md-4 px-5 hide">
    <div class="form-group">
        {!! Form::label('barcode_type', __('lang.barcode_type'), [
            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
        ]) !!}
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
            !empty($recent_product) ? $recent_product->barcode_type : false,
            ['class' => 'form-control', 'required'],
        ) !!}
    </div>
</div>

<div class="col-md-12">
    <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

        <div class="col-md-4 px-5">
            <div class="form-group">
                {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('alert_quantity', !empty($recent_product) ? @num_format($recent_product->alert_quantity) : 3, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.alert_quantity'),
                ]) !!}
            </div>
        </div>
        @can('product_module.purchase_price.create_and_edit')
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('purchase_price', __('lang.cost') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::text('purchase_price', !empty($recent_product) ? @num_format($recent_product->purchase_price) : null, [
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
        <div class="col-md-4 px-5">
            <label for=""
                class="unit_label form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"></label>
        </div>
        <div class="col-md-4 hide">
            <div class="form-group">
                {!! Form::label('alert_quantity_unit_id', __('lang.unit'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select(
                    'alert_quantity_unit_id',
                    $units,
                    !empty($recent_product) ? $recent_product->alert_quantity_unit_id : false,
                    [
                        'class' => 'selectpicker
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'placeholder' => __('lang.please_select'),
                    ],
                ) !!}
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
                <th style="width: 10%;"><button class="btn btn-sm btn-success add_product_row" type="button"><i
                            class="fa fa-plus"></i></button></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<input type="hidden" name="is_raw_material" id="is_raw_material" value="1">
<input type="hidden" name="row_id" id="row_id" value="1">
</div>
