<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('supplier_category_id', __('lang.category') . ':') !!}
            <div class="input-group my-group">
                {!! Form::select('supplier_category_id', $supplier_categories, false, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select'), 'id' => 'supplier_category_id']) !!}
                @if (!$quick_add)
                    <span class="input-group-btn">
                        @can('product_module.product_class.create_and_edit')
                            <button class="btn-modal btn btn-default bg-white btn-flat"
                                data-href="{{ action('SupplierCategoryController@create') }}?quick_add=1"
                                data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                        @endcan
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name', __('lang.representative_name') . ':*') !!}
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('products', __('lang.products')) !!}
            {!! Form::select('products[]', $products, old('products'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'id' => 'products', 'multiple']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('company_name', __('lang.company_name') . ':') !!}
            {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => __('lang.company_name')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('vat_number', __('lang.vat_number') . ':') !!}
            {!! Form::text('vat_number', old('vat_number'), ['class' => 'form-control', 'placeholder' => __('lang.vat_number')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('email', __('lang.email') . ':') !!}
            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => __('lang.email')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('mobile_number', __('lang.mobile_number') . ':') !!}
            {!! Form::text('mobile_number', old('mobile_number'), ['class' => 'form-control', 'placeholder' => __('lang.mobile_number')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('address', __('lang.address') . ':') !!}
            {!! Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => __('lang.balance')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('city', __('lang.city') . ':') !!}
            {!! Form::text('city', old('city'), ['class' => 'form-control', 'placeholder' => __('lang.balance')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('state', __('lang.state') . ':') !!}
            {!! Form::text('state', old('state'), ['class' => 'form-control', 'placeholder' => __('lang.balance')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('postal_code', __('lang.postal_code') . ':') !!}
            {!! Form::text('postal_code', old('postal_code'), ['class' => 'form-control', 'placeholder' => __('lang.balance')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('country    ', __('lang.country') . ':') !!}
            {!! Form::text('country', old('country'), ['class' => 'form-control', 'placeholder' => __('lang.country')]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('photo', __('lang.photo') . ':') !!} <br>
            <div class="container mt-3">
                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                    <div class="col-12">
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <div class="variants">
                                        <div class='file file--upload w-100'>
                                            <label for='file-input' class="w-100">
                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                            </label>
                                            <!-- <input  id="file-input" multiple type='file' /> -->
                                            <input type="file" id="file-input" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-10 offset-1">
                        <div class="preview-container"></div>
                    </div>
                </div>
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


