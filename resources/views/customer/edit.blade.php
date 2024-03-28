@extends('layouts.app')
@section('title', __('lang.customer'))
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
                            @lang('lang.edit_customer')
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
                        'url' => action('CustomerController@update', $customer->id),
                        'id' => 'customer-form',
                        'method' => 'PUT',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}

                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        {!! Form::label('customer_type_id', __('lang.customer_type') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('customer_type_id', $customer_types, $customer->customer_type_id, [
                                            'class' => 'selectpicker
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            form-control',
                                            'data-live-search' => 'true',
                                            'required',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        {!! Form::label('name', __('lang.name'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('name', $customer->name, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.name'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        {!! Form::label('photo', __('lang.photo'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::file('image', ['class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        {!! Form::label('mobile_number', __('lang.mobile_number'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('mobile_number', $customer->mobile_number, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.mobile_number'),
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('email', __('lang.email'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::email('email', $customer->email, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.email'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-group">
                                        {!! Form::label('address', __('lang.address'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::textarea('address', $customer->address, [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'rows' => '3',
                                            'placeholder' => __('lang.address'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#importantDatesCollapse" aria-expanded="false"
                            aria-controls="importantDatesCollapse">
                            <i class="fas fa-arrow-down"></i>
                            @lang('lang.important_dates')
                            <span class="toggle-pill"></span>
                        </button>
                    </div>
                    <div class="collapse" id="importantDatesCollapse">
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless" id="important_date_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('lang.important_date')</th>
                                                        <th>@lang('lang.date')</th>
                                                        <th>@lang('lang.notify_before_days')</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customer->customer_important_dates as $important_date)
                                                        @include('customer.partial.important_date_row', [
                                                            'index' => $loop->index,
                                                            'important_date' => $important_date,
                                                        ])
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="col-md-12 d-flex justify-content-center">
                                                <button type="button"
                                                    class="add_date btn btn-main px-5 py-1">@lang('lang.add_new')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="important_date_index" id="important_date_index"
                            value="{{ $customer->customer_important_dates->count() }}">
                    </div>

                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-4">
                            <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn" class="btn py-1">
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </section>
@endsection

@section('javascript')
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#importantDatesCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#importantDatesCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#importantDatesCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#importantDatesCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
    <script type="text/javascript">
        $('#customer-type-form').submit(function() {
            $(this).validate();
            if ($(this).valid()) {
                $(this).submit();
            }
        })

        $(document).on('click', '.add_date', function() {
            let index = __read_number($('#important_date_index'));
            console.log(index);
            $('#important_date_index').val(index + 1);

            $.ajax({
                method: 'GET',
                url: '/customer/get-important-date-row',
                data: {
                    index: index
                },
                success: function(result) {
                    $('#important_date_table tbody').append(result);
                    $('.datepicker').datepicker()
                },
            });
        })
    </script>
@endsection
