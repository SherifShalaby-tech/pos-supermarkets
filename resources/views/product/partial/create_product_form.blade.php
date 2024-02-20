@php
    $recent_product = App\Models\Product::where('is_raw_material', 0)->orderBy('created_at', 'desc')->first();
    $clear_all_input_form = App\Models\System::getProperty('clear_all_input_form');
@endphp
<div class="card mb-2 d-flex flex-column justify-content-center align-items-center">

    <div class="col-12  d-flex flex-row justify-content-center align-items-center">
        <p class="italic mb-0 py-1">
            <small>@lang('lang.required_fields_info')</small>
        <div style="width: 30px;height: 30px;">
            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
        </div>
        </p>
    </div>

    <div class="col-12 d-flex  flex-row justify-content-between align-items-center">
        <div class="col-md-3 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="is_service" name="is_service" type="checkbox"
                    @if (session('system_mode') == 'restaurant') checked
                @elseif(!empty($recent_product) && $recent_product->is_service == 1) checked @endif
                    class="form-control-custom">
                <label for="is_service"><strong>
                        @if (session('system_mode') == 'restaurant')
                            @lang('lang.or_add_new_product')
                        @else
                            @lang('lang.add_new_service')
                        @endif
                    </strong></label>
            </div>
        </div>
        <div class="col-md-1 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="active" name="active" type="checkbox" checked value="1" class="form-control-custom">
                <label for="active"><strong>
                        @lang('lang.active')
                    </strong></label>
            </div>
        </div>
        <div class="col-md-2 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="have_weight" name="have_weight" type="checkbox" value="1" class="form-control-custom">
                <label for="have_weight"><strong>@lang('lang.have_weight')</strong></label>
            </div>
        </div>
        <div class="col-md-1  px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="weighing_scale_barcode" name="weighing_scale_barcode" type="checkbox"
                    @if (!empty($product->weighing_scale_barcode)) checked @endif value="1" class="form-control-custom">
                <label for="weighing_scale_barcode"><strong>
                        @lang('lang.weighing_scale_barcode')
                    </strong></label>
            </div>
        </div>
        <div class="col-md-3 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="clear_all_input_form" name="clear_all_input_form" type="checkbox"
                    @if ($clear_all_input_form == null || $clear_all_input_form == '1') checked @endif value="1" class="form-control-custom">
                <label for="clear_all_input_form">
                    <strong>
                        @lang('lang.clear_all_input_form')
                    </strong>
                </label>
            </div>
        </div>

        @php
            $products_count = App\Models\Product::where('show_at_the_main_pos_page', 'yes')->count();
        @endphp
        <div class="col-md-2 px-0 d-flex justify-content-center">
            <div class="i-checks">
                <input id="show_at_the_main_pos_page" name="show_at_the_main_pos_page" type="checkbox"
                    @if (isset($products_count) && $products_count > 40) disabled @endif class="form-control-custom">
                <label for="show_at_the_main_pos_page"><strong>@lang('lang.show_at_the_main_pos_page')</strong></label>
            </div>
        </div>
    </div>
</div>
<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        @lang('lang.add_product_information')
        <span class=" section-header-pill"></span>
    </h6>
</div>
<div class="card mb-3">
    <div class="card-body p-2">
        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <div class="col-md-4 px-5">
                <div class="form-group">
                    {!! Form::label('store_ids', __('lang.store'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select('store_ids[]', $stores_select, array_keys($stores_select), [
                        'class' => ' selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'multiple',
                        'id' => 'store_ids',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4 px-5">
                @if (session('system_mode') == 'restaurant')
                    {!! Form::label('product_class_id', __('lang.category') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                @else
                    {!! Form::label('product_class_id', __('lang.class') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                @endif
                <div class="input-group my-group select-button-group">
                    {!! Form::select(
                        'product_class_id',
                        $product_classes,
                        !empty($recent_product) ? $recent_product->product_class_id : false,
                        [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                            'required',
                        ],
                    ) !!}
                    <span class="input-group-btn">
                        @can('product_module.product_class.create_and_edit')
                            <button type="button" class="btn-modal btn-flat select-button "
                                data-href="{{ action('ProductClassController@create') }}?quick_add=1"
                                data-container=".view_modal"><i class="fa fa-plus"></i></button>
                        @endcan
                    </span>
                </div>
                <div class="error-msg text-red"></div>
            </div>

            @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                <div class="col-md-4 px-5">
                    {!! Form::label('category_id', __('lang.category') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        <input type="hidden"
                            data-category_id="{{ !empty($recent_product) ? $recent_product->category_id : null }}"
                            id="category_value_id" />
                        {!! Form::select('category_id', $categories, !empty($recent_product) ? $recent_product->category_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.category.create_and_edit')
                                <button class="btn-modal btn-flat select-button "
                                    data-href="{{ action('CategoryController@create') }}?quick_add=1&type=category"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>
                <div class="col-md-4 px-5">
                    {!! Form::label('sub_category_id', __('lang.sub_category'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {{-- <input type="hidden" data-sub_category_id="{{!empty($recent_product) ? $recent_product->sub_category_id : null}}" id="sub_category_value_id"/> --}}
                        {!! Form::select('sub_category_id', [], !empty($recent_product) ? $recent_product->sub_category_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.sub_category.create_and_edit')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('CategoryController@create') }}?quick_add=1&type=sub_category"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>
                <div class="col-md-4 px-5">
                    {!! Form::label('brand_id', __('lang.brand'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::select('brand_id', $brands, !empty($recent_product) ? $recent_product->brand_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                            'required',
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.brand.create_and_edit')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('BrandController@create') }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>
            @endif
            <div class="col-md-4 px-5">
                <div class="form-group">
                    {!! Form::label('name', __('lang.name') . ' *', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::text('name', null, [
                            'class' => 'form-control clear_input_form modal-input app()->isLocale("ar") ? text-end : text-start',
                            'required',
                            'placeholder' => __('lang.name'),
                        ]) !!}
                        <span class="input-group-btn">
                            <button class="select-button btn-flat translation_btn" type="button"
                                data-type="product"><i class="dripicons-web"></i></button>
                        </span>
                    </div>
                </div>
                @include('layouts.partials.translation_inputs', [
                    'attribute' => 'name',
                    'translations' => [],
                    'type' => 'product',
                ])
            </div>
            <div class="col-md-4 px-5">
                <div class="form-group">
                    {!! Form::label('sku', __('lang.sku'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::text('sku', null, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'id' => 'sku',
                        'placeholder' => __('lang.sku'),
                    ]) !!}
                </div>
            </div>
            @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                <div class="col-md-4 px-5">
                    <div class="form-group">
                        {!! Form::label('alert_quantity', __('lang.alert_quantity'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::text('alert_quantity', !empty($recent_product) ? @num_format($recent_product->alert_quantity) : 3, [
                            'class' => 'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                            'placeholder' => __('lang.alert_quantity'),
                        ]) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div
    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <h6 class="mb-0">
        @lang('lang.add_product_image')
        <span class=" section-header-pill"></span>
    </h6>
</div>

<div class="card mb-3">
    <div
        class="card-body p-2 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
        <div class="variants col-md-6">
            <div class='file file--upload w-100'>
                <label for='file-input' class="w-100   modal-input m-0">
                    <i class="fas fa-cloud-upload-alt"></i>
                </label>
                <!-- <input  id="file-input" multiple type='file' /> -->
                <input type="file" id="file-input">
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <div class="preview-container"></div>
        </div>
    </div>
</div>
{{--        <div style="display: none" class="dropzone" id="my-dropzone"> --}}
{{--            <div style="display: none" class="dz-message" data-dz-message><span>@lang('lang.drop_file_here_to_upload')</span></div> --}}
{{--        </div> --}}

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#productOtherDetailsCollapse" aria-expanded="false"
        aria-controls="productOtherDetailsCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.other_details')
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="productOtherDetailsCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                    <div class="col-md-4 px-5">
                        {!! Form::label('multiple_units', __('lang.unit'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        <div class="input-group my-group select-button-group">
                            {!! Form::select('multiple_units[]', $units, !empty($recent_product) ? $recent_product->multiple_units : false, [
                                'class' => 'clear_input_form selectpicker form-control',
                                'data-live-search' => 'true',
                                'style' => 'width: 80%',
                                'placeholder' => __('lang.please_select'),
                                'id' => 'multiple_units',
                            ]) !!}
                            <span class="input-group-btn">
                                @can('product_module.unit.create_and_edit')
                                    <button class="btn-modal select-button btn-flat"
                                        data-href="{{ action('UnitController@create') }}?quick_add=1"
                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                @endcan
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 px-5">
                        {!! Form::label('multiple_colors', __('lang.color'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        <div class="input-group my-group select-button-group">
                            {!! Form::select(
                                'multiple_colors[]',
                                $colors,
                                !empty($recent_product) ? $recent_product->multiple_colors : false,
                                [
                                    'class' => 'clear_input_form selectpicker form-control',
                                    'data-live-search' => 'true',
                                    'style' => 'width: 80%',
                                    'placeholder' => __('lang.please_select'),
                                    'id' => 'multiple_colors',
                                ],
                            ) !!}
                            <span class="input-group-btn">
                                @can('product_module.color.create_and_edit')
                                    <button class="btn-modal select-button btn-flat"
                                        data-href="{{ action('ColorController@create') }}?quick_add=1"
                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                @endcan
                            </span>
                        </div>
                    </div>
                @endif
                <div class="col-md-4 px-5">
                    {!! Form::label('multiple_sizes', __('lang.size'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::select('multiple_sizes[]', $sizes, !empty($recent_product) ? $recent_product->multiple_sizes : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                            'id' => 'multiple_sizes',
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.size.create_and_edit')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('SizeController@create') }}?quick_add=1"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                </div>
                @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                    <div class="col-md-4 px-5">
                        {!! Form::label('multiple_grades', __('lang.grade'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        <div class="input-group my-group select-button-group">
                            {!! Form::select(
                                'multiple_grades[]',
                                $grades,
                                !empty($recent_product) ? $recent_product->multiple_grades : false,
                                [
                                    'class' => 'clear_input_form selectpicker form-control',
                                    'data-live-search' => 'true',
                                    'style' => 'width: 80%',
                                    'placeholder' => __('lang.please_select'),
                                    'id' => 'multiple_grades',
                                ],
                            ) !!}
                            <span class="input-group-btn">
                                @can('product_module.grade.create_and_edit')
                                    <button class="btn-modal select-button btn-flat"
                                        data-href="{{ action('GradeController@create') }}?quick_add=1"
                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                @endcan
                            </span>
                        </div>
                    </div>
                @endif
                <div class="col-md-4 px-5">
                    <div class="form-group">
                        <label for="printers"
                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif">{{ trans('lang.printers') }}</label>
                        <div class="input-group my-group">
                            <select id="printers" data-live-search="true" class="selectpicker form-control"
                                name="printers[]" multiple>
                                @foreach ($printers as $printer)
                                    <option value="{{ $printer->id }}">{{ $printer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#productDetailsCollapse" aria-expanded="false" aria-controls="productDetailsCollapse">
        <i class="fas fa-arrow-down"></i>
        @if (session('system_mode') == 'restaurant')
            {{ __('lang.recipe') }}
        @else
            @lang('lang.product_details')
        @endif
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="productDetailsCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <button type="button" class="translation_textarea_btn btn btn-sm"><i class="dripicons-web"></i></button>
            <textarea name="product_details" id="product_details" class="form-control" rows="3">{{ !empty($recent_product) ? $recent_product->product_details : '' }}</textarea>

            <div class="col-md-4">
                @include('layouts.partials.translation_textarea', [
                    'attribute' => 'product_details',
                    'translations' => [],
                ])
            </div>
        </div>
    </div>

</div>


@if (session('system_mode') == 'restaurant' || session('system_mode') == 'garments' || session('system_mode') == 'pos')
    <div
        class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
        <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#addPrimaryMaterialCollapse" aria-expanded="false"
            aria-controls="addPrimaryMaterialCollapse">
            <i class="fas fa-arrow-down"></i>
            @lang('lang.add_primary_materials')
            <span class="section-header-pill"></span>
        </button>
    </div>

    <div class="collapse" id="addPrimaryMaterialCollapse">
        <div class="card mb-3">
            <div class="card-body p-2">
                <div class="col-12 d-flex flex-row justify-content-between">

                    <div class="col-md-4">
                        <div class="i-checks">
                            <input id="automatic_consumption" name="automatic_consumption" type="checkbox"
                                value="1" class="form-control-custom">
                            <label for="automatic_consumption"><strong>@lang('lang.automatic_consumption')</strong></label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="i-checks">
                            <input id="price_based_on_raw_material" name="price_based_on_raw_material"
                                type="checkbox" @if (!empty($recent_product) && $recent_product->price_based_on_raw_material == 1) checked @endif value="1"
                                class="form-control-custom">
                            <label for="price_based_on_raw_material"><strong>@lang('lang.price_based_on_raw_material')</strong></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table mb-1" id="consumption_table">
                        <thead>
                            <tr>
                                <th class="py-2 text-center" style="width: 30%;">@lang('lang.raw_materials')</th>
                                <th class="py-2 text-center" style="width: 30%;">@lang('lang.used_amount')</th>
                                <th class="py-2 text-center" style="width: 30%;">@lang('lang.unit')</th>
                                <th class="py-2 text-center" style="width: 30%;">@lang('lang.cost')</th>
                                <th class="py-2 text-center" style="width: 10%;">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('product.partial.raw_material_row', ['row_id' => 0])
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-main px-5 py-1 add_raw_material_row" type="button">
                            @lang('lang.add_new')</button>
                    </div>
                    <input type="hidden" name="raw_material_row_index" id="raw_material_row_index" value="1">
                </div>
            </div>
        </div>
    </div>
@endif

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#discountInfoCollapse" aria-expanded="false" aria-controls="discountInfoCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.discount_information')
        <span class="section-header-pill"></span>
    </button>
</div>

<div class="collapse" id="discountInfoCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="col-md-12">
                <table class="table mb-1" id="consumption_table_discount">
                    <thead>
                        <tr>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_type')</th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount')</th>
                            <th class="py-2 text-center" style="width: 25%;">@lang('lang.discount_category')</th>
                            <th class="py-2 text-center" style="width: 5%;"></th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_start_date')</th>
                            <th class="py-2 text-center" style="width: 15%;">@lang('lang.discount_end_date')</th>
                            <th class="py-2 text-center" style="width: 20%;">@lang('lang.customer_type') <i
                                    class="dripicons-question" data-toggle="tooltip" title="@lang('lang.discount_customer_info')"></i>
                            </th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @include('product.partial.raw_discount', ['row_id' => 0]) --}}
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-main px-5 py-1 add_discount_row" type="button">@lang('lang.add_new')</button>
                </div>
                <input type="hidden" name="raw_discount_index" id="raw_discount_index" value="1">
            </div>
        </div>
    </div>
</div>


<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#moreInfoCollapse" aria-expanded="false" aria-controls="moreInfoCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.more_info')
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="moreInfoCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket')
                    <div class="col-md-3 px-5">
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
                @endif

                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('other_cost', __('lang.other_cost'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::text('other_cost', !empty($recent_product) ? @num_format($recent_product->other_cost) : null, [
                            'class' => 'form-control clear_input_form modal-input app()->isLocale("ar") ? text-end : text-start',
                            'placeholder' => __('lang.other_cost'),
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 px-5">
                    {!! Form::label('tax_id', __('lang.tax'), [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    <div class="input-group my-group select-button-group">
                        {!! Form::select('tax_id', $taxes, !empty($recent_product) ? $recent_product->tax_id : false, [
                            'class' => 'clear_input_form selectpicker form-control',
                            'data-live-search' => 'true',
                            'style' => 'width: 80%',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                        <span class="input-group-btn">
                            @can('product_module.tax.create')
                                <button class="btn-modal select-button btn-flat"
                                    data-href="{{ action('TaxController@create') }}?quick_add=1&type=product_tax"
                                    data-container=".view_modal"><i class="fa fa-plus"></i></button>
                            @endcan
                        </span>
                    </div>
                    <div class="error-msg text-red"></div>
                </div>

                <div class="col-md-3 px-5">
                    <div class="form-group">
                        {!! Form::label('tax_method', __('lang.tax_method'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!}
                        {!! Form::select(
                            'tax_method',
                            ['inclusive' => __('lang.inclusive'), 'exclusive' => __('lang.exclusive')],
                            !empty($recent_product) ? $recent_product->tax_method : false,
                            [
                                'class' => 'clear_input_form selectpicker form-control',
                                'data-live-search' => 'true',
                                'style' => 'width: 80%',
                                'placeholder' => __('lang.please_select'),
                            ],
                        ) !!}
                    </div>
                </div>
                <div
                    class="d-flex col-12 flex-column @if (app()->isLocale('ar')) align-items-end @else  align-items-start @endif
                    ">
                    <div
                        class="col-md-4 d-flex @if (app()->isLocale('ar')) justify-content-end @else  justify-content-start @endif">
                        <div class="i-checks">
                            <input id="show_to_customer" name="show_to_customer" type="checkbox" checked
                                value="1" class="form-control-custom">
                            <label for="show_to_customer"><strong>@lang('lang.show_to_customer')</strong></label>
                        </div>
                    </div>
                    <div class="col-md-3 show_to_customer_type_div">
                        <div class="form-group">
                            {!! Form::label('show_to_customer_types', __('lang.show_to_customer_types'), [
                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                            ]) !!}
                            <i class="dripicons-question" data-toggle="tooltip" title="@lang('lang.show_to_customer_types_info')"></i>
                            {!! Form::select(
                                'show_to_customer_types[]',
                                $customer_types,
                                !empty($recent_product) ? $recent_product->show_to_customer_types : false,
                                ['class' => ' selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'multiple'],
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#pricesFromDifferentStoresCollapse" aria-expanded="false"
        aria-controls="pricesFromDifferentStoresCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.prices_from_different_stores')
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="pricesFromDifferentStoresCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            {{-- <div class="col-md-12" style="margin-top: 10px">
                <div class="i-checks">
                    <input id="different_prices_for_stores" name="different_prices_for_stores" type="checkbox"
                        value="1" class="form-control-custom">
                    <label for="different_prices_for_stores"><strong>@lang('lang.different_prices_for_stores')</strong></label>
                </div>
            </div> --}}

            <div class="col-md-12 ">
                <table class="table mb-1">
                    <thead>
                        <tr>
                            <th class="py-2 text-center px-1">
                                @lang('lang.store')
                            </th>
                            <th class="py-2 text-center px-1">
                                @lang('lang.price')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $store)
                            <tr>
                                <td class="py-2 px-1 d-flex justify-content-center align-items-center">
                                    {{ $store->name }}</td>
                                <td class="py-2 px-1 text-center"><input type="text"
                                        class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif store_prices"
                                        style="width: 25% !important"
                                        name="product_stores[{{ $store->id }}][price]" value=""></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#varientCollapse" aria-expanded="false" aria-controls="varientCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.has_varient')
        <span class="section-header-pill"></span>
    </button>
</div>
<div class="collapse" id="varientCollapse">
    <div class="card mb-3">
        <div class="card-body p-2">
            {{-- <div class="col-md-12" style="margin-top: 10px">
                <div class="i-checks">
                    <input id="this_product_have_variant" name="this_product_have_variant" type="checkbox"
                        value="1" class="form-control-custom">
                    <label for="this_product_have_variant"><strong>@lang('lang.this_product_have_variant')</strong></label>
                </div>
            </div> --}}

            <div class="col-md-12 " style="overflow: auto">
                <table class="table mb-1" id="variation_table">
                    <thead>
                        <tr>
                            <th class="py-2 text-center px-1">@lang('lang.name')</th>
                            <th class="py-2 text-center px-1">@lang('lang.sku')</th>
                            <th class="py-2 text-center px-1">@lang('lang.color')</th>
                            <th class="py-2 text-center px-1">@lang('lang.size')</th>
                            <th class="py-2 text-center px-1">@lang('lang.grade')</th>
                            <th class="py-2 text-center px-1">@lang('lang.unit')</th>
                            <th class="py-2 text-center px-1">@lang('lang.number_vs_base_unit')</th>
                            {{-- @if (empty($is_service)) hide @endif --}}
                            <th class="default_purchase_price_th @if (empty($is_service)) hide @endif">
                                @lang('lang.purchase_price')</th>
                            <th class="default_sell_price_th @if (empty($is_service)) hide @endif">
                                @lang('lang.sell_price')
                            </th>
                            <th class="py-2 text-center px-1">
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-main px-5 py-1 add_row">@lang('lang.add_new')</button>
                </div>
            </div>
        </div>
    </div>
</div>



@can('product_module.purchase_price.create_and_edit')
    <div class="col-md-4 supplier_div">
        <div class="form-group">
            {!! Form::label(
                'purchase_price',
                session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket'
                    ? __('lang.purchase_price')
                    : __('lang.cost') . ' *',
                [],
            ) !!}
            {!! Form::text('purchase_price', !empty($recent_product) ? @num_format($recent_product->purchase_price) : null, [
                'class' => 'clear_input_form form-control',
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

<div class="col-md-4 supplier_div">
    <div class="form-group">
        {!! Form::label('sell_price', __('lang.sell_price') . ' *', []) !!}
        {!! Form::text('sell_price', !empty($recent_product) ? @num_format($recent_product->sell_price) : null, [
            'class' => 'clear_input_form form-control',
            'placeholder' => __('lang.sell_price'),
            'required',
        ]) !!}
    </div>
</div>



<div class="clearfix"></div>


<input type="hidden" name="default_purchase_price_percentage" id="default_purchase_price_percentage"
    value="{{ App\Models\System::getProperty('default_purchase_price_percentage') ?? 75 }}">
<input type="hidden" name="default_profit_percentage" id="default_profit_percentage"
    value="{{ App\Models\System::getProperty('default_profit_percentage') ?? 0 }}">








<input type="hidden" name="row_id" id="row_id" value="0">
