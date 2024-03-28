@extends('layouts.app')
@section('title', __('lang.supplier'))

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/supplier.css') }}">
@endsection

@section('content')
    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div
                        class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <h5 class="mb-0 position-relative" style="margin-right: 30px">@lang('lang.edit_supplier')
                            <span class="header-pill"></span>
                        </h5>
                    </div>
                    <div class="card mb-2 d-flex flex-row justify-content-center align-items-center">
                        <p class="italic mb-0 py-1"><small>@lang('lang.required_fields_info')</small>
                        <div style="width: 30px;height: 30px;">
                            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
                        </div>
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body p-2">
                            {!! Form::open([
                                'url' => action('SupplierController@update', $supplier->id),
                                'id' => 'supplier-form',
                                'method' => 'PUT',
                                'class' => '',
                                'enctype' => 'multipart/form-data',
                            ]) !!}

                            <div class="row">
                                <div class="col-md-6 px-5">
                                    {!! Form::label('name', __('lang.representative_name') . '*', [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('name', $supplier->name, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'placeholder' => __('lang.name'),
                                        'required',
                                    ]) !!}
                                </div>

                                <div class="col-md-6 px-5">
                                    {!! Form::label('company_name', __('lang.company_name'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('company_name', $supplier->company_name, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'placeholder' => __('lang.company_name'),
                                    ]) !!}
                                </div>
                            </div>

                            <div
                                class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                <button class="text-decoration-none toggle-button mb-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#supplierCollapse" aria-expanded="false"
                                    aria-controls="supplierCollapse">
                                    <i class="fas fa-arrow-down"></i>
                                    @lang('lang.other_details')
                                    <span class="toggle-pill"></span>
                                </button>
                            </div>

                            <div class="collapse show" id="supplierCollapse">
                                <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('supplier_category_id', __('lang.category'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        <div class="input-group my-group select-button-group">
                                            {!! Form::select('supplier_category_id', $supplier_categories, $supplier->supplier_category_id, [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'style' => 'width: 80%',
                                                'placeholder' => __('lang.please_select'),
                                                'id' => 'supplier_category_id',
                                            ]) !!}
                                            <span class="input-group-btn">
                                                @can('product_module.product_class.create_and_edit')
                                                    <button class="btn-modal select-button btn-flat"
                                                        data-href="{{ action('SupplierCategoryController@create') }}?quick_add=1"
                                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                                @endcan
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('products', __('lang.products'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('products[]', $products, $supplier->supplier_products->pluck('product_id'), [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'placeholder' => __('lang.please_select'),
                                            'id' => 'products',
                                            'multiple',
                                        ]) !!}
                                    </div>


                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('vat_number', __('lang.vat_number'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('vat_number', $supplier->vat_number, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.vat_number'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('email', __('lang.email'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::email('email', $supplier->email, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.email'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('mobile_number', __('lang.mobile_number'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('mobile_number', $supplier->mobile_number, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.mobile_number'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('address', __('lang.address'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('address', $supplier->address, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.balance'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('city', __('lang.city'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('city', $supplier->city, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.balance'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('state', __('lang.state'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('state', $supplier->state, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.balance'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('postal_code', __('lang.postal_code'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('postal_code', $supplier->postal_code, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.balance'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-3 px-5 mb-2">
                                        {!! Form::label('country ', __('lang.country'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('country ', $supplier->country, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.country'),
                                        ]) !!}
                                    </div>
                                    <div class="col-md-6 px-5 mb-2">
                                        {!! Form::label('photo', __('lang.photo'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::file('image', ['class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row my-2 justify-content-center align-items-center">
                            <div class="col-md-4">
                                <input type="submit" value="{{ trans('lang.submit') }}" id="submit-btn" class="btn py-1">
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $('#supplier-type-form').submit(function() {
            $(this).validate();
            if ($(this).valid()) {
                $(this).submit();
            }
        });
        $(document).on("submit", "form#quick_add_supplier_category_form", function(e) {
            $("form#quick_add_supplier_category_form").validate();
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        swal("Success", result.msg, "success");
                        $(".view_modal").modal("hide");
                        var supplier_category_id = result.supplier_category_id;
                        $.ajax({
                            method: "get",
                            url: "/supplier-category/get-dropdown",
                            data: {},
                            contactType: "html",
                            success: function(result) {
                                $("select#supplier_category_id").html(result);
                                $("select#supplier_category_id").val(supplier_category_id);
                                $("#supplier_category_id").selectpicker("refresh");

                            },
                        });
                    } else {
                        swal("Error", result.msg, "error");
                    }
                },
            });
        });
    </script>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>


    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#supplierCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#supplierCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#supplierCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#supplierCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
@endsection
