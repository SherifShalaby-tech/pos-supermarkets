<div class="row">
    <div class="col-md-6 px-5">
        <label for="name"
            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif">{{ __('lang.representative_name') }}</label>
        {!! Form::text('name', old('name'), [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
            'placeholder' => __('lang.name'),
            'required',
        ]) !!}
    </div>
    <div class="col-md-6 px-5">

        <label for="company_name"
            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif">{{ __('lang.company_name') }}</label>
        {!! Form::text('company_name', old('company_name'), [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
            'placeholder' => __('lang.company_name'),
        ]) !!}
    </div>
</div>

<div class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
    <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#supplierCollapse" aria-expanded="false" aria-controls="supplierCollapse">
        <i class="fas fa-arrow-down"></i>
        @lang('lang.other_details')
        <span class="toggle-pill"></span>
    </button>
</div>


<div class="collapse" id="supplierCollapse">
    <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
        <div class="col-md-3 px-5">
            {!! Form::label('supplier_category_id', __('lang.category'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            <div class="input-group my-group select-button-group">
                {!! Form::select('supplier_category_id', $supplier_categories, false, [
                    'class' => 'selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'placeholder' => __('lang.please_select'),
                    'id' => 'supplier_category_id',
                ]) !!}
                @if (!$quick_add)
                    <span class="input-group-btn">
                        @can('product_module.product_class.create_and_edit')
                            <button class="select-button btn-flat btn-modal"
                                data-href="{{ action('SupplierCategoryController@create') }}?quick_add=1"
                                data-container=".view_modal"><i class="fa fa-plus"></i></button>
                        @endcan
                    </span>
                @endif
            </div>
        </div>

        <div class="col-md-3 px-5">

            {!! Form::label('products', __('lang.products'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::select('products[]', $products, old('products'), [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'data-actions-box' => 'true',
                'id' => 'products',
                'multiple',
            ]) !!}

        </div>


        <div class="col-md-3 px-5">

            {!! Form::label('vat_number', __('lang.vat_number'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('vat_number', old('vat_number'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.vat_number'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('email', __('lang.email'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::email('email', old('email'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.email'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('mobile_number', __('lang.mobile_number'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('mobile_number', old('mobile_number'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.mobile_number'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('address', __('lang.address'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('address', old('address'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.balance'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('city', __('lang.city'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('city', old('city'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.balance'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('state', __('lang.state'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('state', old('state'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.balance'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('postal_code', __('lang.postal_code'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('postal_code', old('postal_code'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.balance'),
            ]) !!}

        </div>
        <div class="col-md-3 px-5">

            {!! Form::label('country    ', __('lang.country'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('country', old('country'), [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.country'),
            ]) !!}

        </div>
        <div class="col-md-6 px-5">

            {!! Form::label('photo', __('lang.photo'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}


            <div class="variants">
                <div class='file file--upload w-100'>
                    <label for='file-input' class="w-100  modal-input m-0">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </label>
                    <!-- <input  id="file-input" multiple type='file' /> -->
                    <input type="file" id="file-input">
                </div>
            </div>

            <div class="col-12 d-flex justify-content-center">
                <div class="preview-container"></div>
            </div>




        </div>
    </div>
</div>



<input type="hidden" name="quick_add" value="{{ $quick_add }}">
<div id="cropped_images"></div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="croppie-modal" style="display:none">
                    <div id="croppie-container"></div>
                    <button data-dismiss="modal" id="croppie-cancel-btn" type="button" class="btn btn-secondary"><i
                            class="fas fa-times"></i></button>
                    <button id="croppie-submit-btn" type="button" class="btn btn-primary"><i
                            class="fas fa-crop"></i></button>
                </div>
            </div>

        </div>
    </div>
</div>
