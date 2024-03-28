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
                            @lang('lang.add_customer')
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
                        'url' => action('CustomerController@store'),
                        'id' => 'customer-form',
                        'method' => 'POST',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    @include('customer.partial.create_customer_form')
                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-4">
                            <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn" class="btn py-1">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal gift_card_modal no-print" role="dialog" aria-hidden="true"></div>
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
        $('#referralCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#referralCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#referralCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#referralCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
    <script src="{{ asset('js/customer_pst.js') }}"></script>
    <script type="text/javascript">
        $('#customer-type-form').submit(function() {
            $(this).validate();
            if ($(this).valid()) {
                $(this).submit();
            }
        });
        $('.add_size_btn').click(function() {
            $('.add_size_div').removeClass('hide');
        });
        $(document).on('change', '.cm_size', function() {
            let row = $(this).closest('tr');
            let cm_size = __read_number(row.find('.cm_size'));
            let inches_size = cm_size * 0.393701;

            __write_number(row.find('.inches_size'), inches_size);

            let name = $(this).data('name');
            show_value(row, name)
        })
        $(document).on('change', '.inches_size', function() {
            let row = $(this).closest('tr');
            let inches_size = __read_number(row.find('.inches_size'));
            let cm_size = inches_size * 2.54;

            __write_number(row.find('.cm_size'), cm_size);

            let name = $(this).data('name');
            show_value(row, name)
        })

        function show_value(row, name) {
            let cm_size = __read_number(row.find('.cm_size'));

            $('.' + name + '_span').text(cm_size);
        }
        $(document).on('click', '.add_date', function() {
            let index = __read_number($('#important_date_index'));

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
        });

        $(document).on('change', 'select.payment_status', function() {
            var payment_status = $(this).val();
            let referred_row = $(this).parents(".referred_row");

            if (payment_status === 'paid' || payment_status === 'partial') {
                $(referred_row).find('.not_cash_fields').addClass('hide');
                $(referred_row).find('select.method').change();
                $(referred_row).find('select.method').attr('required', true);
                $(referred_row).find('#paid_on').attr('required', true);
                $(referred_row).find('.payment_fields').removeClass('hide');
            } else {
                $(referred_row).find('.payment_fields').addClass('hide');
            }
            if (payment_status === 'pending' || payment_status === 'partial') {
                $(referred_row).find('.due_fields').removeClass('hide');
            } else {
                $(referred_row).find('.due_fields').addClass('hide');
            }
            if (payment_status === 'pending') {
                $(referred_row).find('.not_cash_fields').addClass('hide');
                $(referred_row).find('.not_cash').attr('required', false);
                $(referred_row).find('select.method').attr('required', false);
                $(referred_row).find('#paid_on').attr('required', false);
            } else {
                $(referred_row).find('select.method').attr('required', true);
            }
            if (payment_status === 'paid') {
                $(referred_row).find('.due_fields').addClass('hide');
            }
        });

        $(document).on('change', 'select.source_type', function() {
            let referred_row = $(this).parents(".referred_row");
            if ($(this).val() !== '') {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-source-by-type-dropdown/' + $(this).val(),
                    data: {},
                    success: function(result) {
                        $(referred_row).find("select.source_id").html(result);
                        $(referred_row).find("select.source_id").selectpicker("refresh");
                    },
                });
            }
        });

        $(document).on('change', 'select.method', function() {
            var method = $(this).val();
            let referred_row = $(this).parents(".referred_row");
            if (method === 'cash') {
                $(referred_row).find('.not_cash_fields').addClass('hide');
                $(referred_row).find('.not_cash').attr('required', false);
            } else {
                $(referred_row).find('.not_cash_fields').removeClass('hide');
                $(referred_row).find('.not_cash').attr('required', true);
            }
        });

        $('.datepicker').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });
    </script>

@endsection
