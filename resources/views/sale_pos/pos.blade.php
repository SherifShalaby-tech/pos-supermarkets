@extends('layouts.app')
@section('title', __('lang.pos'))
@section('content')

@section('style')
    <link rel="stylesheet" href="{{ url('css/pos.css') }}">
@endsection



@php
    $watsapp_numbers = App\Models\System::getProperty('watsapp_numbers');
@endphp

<section class="p-0">
    <audio id="mysoundclip1" preload="auto">
        <source src="{{ asset('audio/beep-timber.mp3') }}">
        </source>
    </audio>
    <audio id="mysoundclip2" preload="auto">
        <source src="{{ asset('audio/beep-07.mp3') }}">
        </source>
    </audio>
    <audio id="mysoundclip3" preload="auto">
        <source src="{{ asset('audio/beep-long.mp3') }}">
        </source>
    </audio>

    {{-- Navbar --}}
    <div class=" d-flex flex-column flex-lg-row justify-content-between align-items-center px-4"
        style="background-color: #A0D8A1;padding-top: 5px;padding-bottom:5px">

        <div class="d-flex justify-content-center align-items-center">
            <a id="toggle-btn" href="#" class="menu-btn">
                <div class=" rounded-lg px-1 d-flex justify-content-center align-items-center"
                    style="background-color: white;border-radius: 6px;width: 24px;height: 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em"
                        viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" />
                    </svg>
                </div>
            </a>
        </div>

        <div class="d-flex justify-content-start flex-grow-1 align-items-center">

            <a href="{{ action('SellController@create') }}" id="commercial_invoice_btn" data-toggle="tooltip"
                data-title="@lang('lang.add_sale')"
                class="btn no-print font-weight-bold d-flex justify-content-center align-items-center py-0 mr-1 mr-lg-2"
                style="color: black;background-color: white; font-size: 14px">
                {{-- <i class="fas fa-receipt"></i> --}}
                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                    viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <style>
                        svg {
                            fill: #000
                        }
                    </style>
                    <path
                        d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM80 64h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16s7.2-16 16-16zm16 96H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V256c0-17.7 14.3-32 32-32zm0 32v64H288V256H96zM240 416h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H240c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                </svg>
                {{-- <i class="fa-file-invoice"></i> --}}
                <span class=" ml-1">Invoices</span>
            </a>




            <button
                class="btn-danger btn btn-sm hide font-weight-bold d-flex justify-content-center align-items-center py-0 px-3 mr-1 mr-lg-2"
                id="power_off_btn" style="color: white;font-size: 15px;width: 24px;height: 24px; border-radius: 8px;"><i
                    class="fa fa-power-off"></i>
            </button>

            {{-- <div class="inline" style="width: 30px;height: 30px;"> --}}
            <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $watsapp_numbers }}" id="contact_us_btn"
                data-toggle="tooltip" data-title="@lang('lang.contact_us')"
                style="color: black;background-color: white; font-size: 20px;width: 24px;height: 24px; border-radius: 8px;"
                class=" no-print  mr-1 mr-lg-2 d-flex justify-content-center align-items-center">
                <img src="{{ asset('images/watsapp.jpg') }}" style="width: 85%" alt="">
            </a>
            {{-- </div> --}}

            @php
                $config_languages = config('constants.langs');
                $languages = [];
                foreach ($config_languages as $key => $value) {
                    $languages[$key] = $value['full_name'];
                }
            @endphp

            <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"
                class=" dropdown-item  mr-1 mr-lg-2 d-flex justify-content-center align-items-center"
                style="color: black;background-color: white; font-size: 15px;width: 24px;height: 24px; border-radius: 8px;"><i
                    class="dripicons-web"></i>

            </a>
            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                @foreach ($languages as $key => $lang)
                    <li>
                        <a href="{{ action('GeneralController@switchLanguage', $key) }}" class="btn btn-link">
                            {{ $lang }}</a>
                    </li>
                @endforeach

            </ul>

            @include('layouts.partials.notification_list')

            <li class=" mr-1 mr-lg-2 d-flex justify-content-center align-items-center"
                style="color: black;background-color: white; font-size: 15px;width: 24px;height: 24px; border-radius: 8px;list-style: none">
                <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" class=" dropdown-item d-flex justify-content-center align-items-center"
                    style="padding: 6px">

                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                        viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                    </svg>

                </a>
                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                    @php
                        $employee = App\Models\Employee::where('user_id', Auth::user()->id)->first();
                    @endphp
                    <li style="text-align: center">
                        <img src="@if (!empty($employee->getFirstMediaUrl('employee_photo'))) {{ $employee->getFirstMediaUrl('employee_photo') }}@else{{ asset('images/default.jpg') }} @endif"
                            style="width: 60px; border: 2px solid #fff; padding: 4px; border-radius: 50%;" />
                    </li>
                    <li>
                        <a href="{{ action('UserController@getProfile') }}"><i class="dripicons-user"></i>
                            @lang('lang.profile')</a>
                    </li>
                    @can('settings.general_settings.view')
                        <li>
                            <a href="{{ action('SettingController@getGeneralSetting') }}"><i class="dripicons-gear"></i>
                                @lang('lang.settings')</a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ url('my-transactions/' . date('Y') . '/' . date('m')) }}"><i
                                class="dripicons-swap"></i>
                            @lang('lang.my_transactions')</a>
                    </li>
                    @if (Auth::user()->role_id != 5)
                        <li>
                            <a href="{{ url('my-holidays/' . date('Y') . '/' . date('m')) }}"><i
                                    class="dripicons-vibrate"></i>
                                @lang('lang.my_holidays')</a>
                        </li>
                    @endif

                    <li>
                        <a href="#" id="logout-btn"><i class="dripicons-power"></i>
                            @lang('lang.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>


            <a class=" dropdown-item  mr-1 mr-lg-2 d-flex justify-content-center align-items-center"
                style="color: black;background-color: white; font-size: 15px;width: 24px;height: 24px; border-radius: 8px;padding: 5px"
                id="btnFullscreen" title="Full Screen">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                    viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M160 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V64zM32 320c-17.7 0-32 14.3-32 32s14.3 32 32 32H96v64c0 17.7 14.3 32 32 32s32-14.3 32-32V352c0-17.7-14.3-32-32-32H32zM352 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V64zM320 320c-17.7 0-32 14.3-32 32v96c0 17.7 14.3 32 32 32s32-14.3 32-32V384h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320z" />
                </svg>
            </a>


        </div>
    </div>

    <div class="p-1 d-flex flex-column flex-lg-row">
        <div class="col-lg-10 p-1">

            <div class="bg-white mb-2 py-2 d-flex flex-wrap justify-content-between align-items-center"
                style="border-radius: 8px;width: 100%">

                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="mb-2 mb-lg-0  height-responsive d-flex justify-content-center align-items-center"
                        style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 4px 6px;
                                    width: 100%;
                                    ">
                        {{-- {!! Form::label('store_id', __('lang.store') . ':*', []) !!} --}}
                        {!! Form::select('store_id', $stores, $store_pos->store_id, [
                            'class' => 'selectpicker first-head walk-in-customer',
                            'data-live-search' => 'true',
                            'required',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                    </div>
                </div>
                {{--  --}}
                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                        style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 4px 6px;
                                    width: 100%;
                                    ">
                        {{-- {!! Form::label('store_pos_id', __('lang.pos') . ':*', []) !!} --}}
                        {!! Form::select('store_pos_id', $store_poses, $store_pos->id, [
                            'class' => 'selectpicker first-head  walk-in-customer',
                            'data-live-search' => 'true',
                            'required',
                            'placeholder' => __('lang.pos'),
                        ]) !!}
                    </div>
                </div>

                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                        style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 4px 6px;
                                    width: 100%;
                                    ">
                        <input type="hidden" name="setting_invoice_lang" id="setting_invoice_lang"
                            value="{{ !empty(App\Models\System::getProperty('invoice_lang')) ? App\Models\System::getProperty('invoice_lang') : 'en' }}">
                        {{-- {!! Form::label('invoice_lang', __('lang.invoice_lang') . ':', []) !!} --}}
                        {!! Form::select(
                            'invoice_lang',
                            $languages + ['ar_and_en' => 'Arabic and English'],
                            !empty(App\Models\System::getProperty('invoice_lang')) ? App\Models\System::getProperty('invoice_lang') : 'en',
                            ['class' => 'first-head walk-in-customer selectpicker', 'data-live-search' => 'true'],
                        ) !!}
                    </div>
                </div>

                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                        style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 4px 6px;
                                    width: 100%;
                                   ">
                        <input type="hidden" name="exchange_rate" id="exchange_rate" value="1">
                        <input type="hidden" name="default_currency_id" id="default_currency_id"
                            value="{{ !empty(App\Models\System::getProperty('currency')) ? App\Models\System::getProperty('currency') : '' }}">
                        {{-- {!! Form::label('received_currency_id', __('lang.received_currency') . ':', []) !!} --}}
                        {!! Form::select(
                            'received_currency_id',
                            $exchange_rate_currencies,
                            !empty(App\Models\System::getProperty('currency')) ? App\Models\System::getProperty('currency') : null,
                            ['class' => 'first-head walk-in-customer selectpicker', 'data-live-search' => 'true'],
                        ) !!}
                    </div>
                </div>

                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="form-group tax mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                        style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 4px 6px;
                                    width: 100%;
                                    ">
                        <select class="form-control" name="tax_id" id="tax_id"
                            style="background-color: transparent">
                            <option value="">No Tax</option>
                            @foreach ($taxes as $tax)
                                <option data-rate="{{ $tax['rate'] }}"
                                    @if (!empty($transaction) && $transaction->tax_id == $tax['id']) selected @endif value="{{ $tax['id'] }}">
                                    {{ $tax['name'] }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="tax_id_hidden" id="tax_id_hidden" value="">
                        <input type="hidden" name="tax_method" id="tax_method" value="">
                        <input type="hidden" name="tax_rate" id="tax_rate" value="0">
                        <input type="hidden" name="tax_type" id="tax_type" value="">
                    </div>
                </div>

                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="col-6">
                        <button type="button"
                            class="btn btn-link btn-sm d-flex justify-content-center align-items-center height-responsive"
                            style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 12px;
                                    width: fit-content;
                                   "
                            data-toggle="modal" data-target="#delivery-cost-modal">

                            <svg xmlns="http://www.w3.org/2000/svg" height="2em"
                                viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M280 32c-13.3 0-24 10.7-24 24s10.7 24 24 24h57.7l16.4 30.3L256 192l-45.3-45.3c-12-12-28.3-18.7-45.3-18.7H64c-17.7 0-32 14.3-32 32v32h96c88.4 0 160 71.6 160 160c0 11-1.1 21.7-3.2 32h70.4c-2.1-10.3-3.2-21-3.2-32c0-52.2 25-98.6 63.7-127.8l15.4 28.6C402.4 276.3 384 312 384 352c0 70.7 57.3 128 128 128s128-57.3 128-128s-57.3-128-128-128c-13.5 0-26.5 2.1-38.7 6L418.2 128H480c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32H459.6c-7.5 0-14.7 2.6-20.5 7.4L391.7 78.9l-14-26c-7-12.9-20.5-21-35.2-21H280zM462.7 311.2l28.2 52.2c6.3 11.7 20.9 16 32.5 9.7s16-20.9 9.7-32.5l-28.2-52.2c2.3-.3 4.7-.4 7.1-.4c35.3 0 64 28.7 64 64s-28.7 64-64 64s-64-28.7-64-64c0-15.5 5.5-29.7 14.7-40.8zM187.3 376c-9.5 23.5-32.5 40-59.3 40c-35.3 0-64-28.7-64-64s28.7-64 64-64c26.9 0 49.9 16.5 59.3 40h66.4C242.5 268.8 190.5 224 128 224C57.3 224 0 281.3 0 352s57.3 128 128 128c62.5 0 114.5-44.8 125.8-104H187.3zM128 384a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                            </svg>

                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="print_and_draft"
                            class="btn btn-link btn-sm d-flex justify-content-center align-items-center height-responsive"
                            style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 12px;
                                    width: fit-content;
                                   ">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                            </svg>
                            < </button>
                                <input type="hidden" id="print_and_draft_hidden" name="print_and_draft_hidden"
                                    value="">
                    </div>
                </div>

            </div>

            <div style="border-radius: 8px;width: 100%"
                class="py-2 d-flex bg-white flex-row justify-content-between align-items-start">
                <div class="col-6 d-flex flex-column flex-lg-row">
                    <div class="col-lg-4 mb-2 mb-lg-0">

                        <div class="col-12 form-group input-group my-group d-flex flex-row justify-content-center height-responsive"
                            style="background-color: #dedede; border: none;
                                        border-radius: 16px;
                                        color: #373737;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        width: 100%;
                                        margin: auto;
                                        flex-wrap: nowrap;
                                        padding-right:25px">
                            {!! Form::select('customer_id', $customers, !empty($walk_in_customer) ? $walk_in_customer->id : null, [
                                'class' => 'selectpicker first-head  walk-in-customer walk-in-customer',
                                'data-live-search' => 'true',
                                'style' => 'width: 80%',
                                'id' => 'customer_id',
                                'required',
                            ]) !!}
                            <span class="input-group-btn">
                                @can('customer_module.customer.create_and_edit')
                                    <a style="background-color: #F9C751;
                                        width: 100%;
                                        height: 100%;
                                        border-radius: 16px;
                                        padding: 6px 6px;
                                        "
                                        class="d-flex justify-content-center align-items-center top-0 right-0"
                                        data-href="{{ action('CustomerController@create') }}?quick_add=1"
                                        data-container=".view_modal">
                                        <svg class="plus" xmlns="http://www.w3.org/2000/svg" height="2em"
                                            viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <style>
                                                .plus {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                        </svg>
                                    </a>
                                @endcan
                            </span>

                        </div>
                    </div>

                    <div class="col-lg-4 mb-2 mb-lg-0">
                        <div style="width: 100%"
                            class="col-12 first-head p-0 input-group my-group d-flex flex-row justify-content-center height-responsive">
                            <button type="button"
                                style="background-color: #dedede;
                                    border: none;
                                        border-radius: 16px;
                                        color: #373737;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        padding: 10px 6px;
                                        width: 100%;"
                                class="height-responsive d-flex justify-content-center align-items-center"
                                data-toggle="modal" data-target="#contact_details_modal">@lang('lang.details')
                            </button>
                        </div>

                    </div>

                    <div class="col-lg-4 mb-2 mb-lg-0">
                        <div style="width: 100%"
                            class="col-12 first-head p-0  input-group my-group d-flex flex-row justify-content-center height-responsive">

                            <button type="button"
                                style="background-color: #dedede;
                                    border: none;
                                        border-radius: 16px;
                                        color: #373737;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        padding: 10px 6px;
                                        width: 100%;"
                                class="height-responsive d-flex justify-content-center align-items-center"
                                data-toggle="modal" data-target="#non_identifiable_item_modal">@lang('lang.non_identifiable_item')
                            </button>

                        </div>
                    </div>
                </div>


                <div class="col-6 d-flex flex-column flex-lg-row">
                    <div class="col-lg-4 mb-2 mb-lg-0">

                        <div class="col-lg-12 mb-0 ml-1 input-group my-group d-flex justify-content-between height-responsive text-center"
                            style="background-color: white; border: none;
                            border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    width: 100%;
                                            flex-wrap: nowrap;font-size: 10px;padding:0">


                            <label class="d-none justify-content-center align-items-center height-responsive"
                                style="background-color: #21912A;
                                                width: 100%;

                                                border-radius: 16px;
                                                padding: 10px 12px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                padding:0;

                                                "
                                for="customer_type_name">
                                @lang('lang.customer_type'):
                            </label>
                            <span style="color: #000;width: 100%;"
                                class="customer_type_name d-flex justify-content-center align-items-center height-responsive"></span>

                        </div>
                    </div>

                    <div class="col-lg-4 mb-2 mb-lg-0">
                        <div class="col-12 p-0 ml-1 input-group my-group d-flex flex-row justify-content-between height-responsive text-center"
                            style="background-color: white; border: none;
                            border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    width: 100%;
                               ">
                            <label
                                class="d-flex justify-content-center justify-content-md-between align-items-center height-responsive"
                                for="customer_balance"
                                style="background-color: #21912A;
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;
                                                padding: 10px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">@lang('lang.balance'):
                            </label>
                            <span style="color: #000; padding-right: 15px"
                                class="customer_balance d-flex justify-content-start align-items-center">{{ @num_format(0) }}</span>

                        </div>
                    </div>

                    <div class="col-lg-4 mb-2 mb-lg-0">
                        <div class="col-12 p-0 ml-1 input-group my-group d-flex flex-row justify-content-between height-responsive text-center"
                            style="background-color: white; border: none;
                            border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    width: 100%">
                            <label for="points"
                                class="d-flex justify-content-center justify-content-md-between align-items-center height-responsive"
                                style="background-color: #21912A;
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;
                                                padding: 10px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">
                                @lang('lang.points'):
                            </label>
                            <span style="color: #000; padding-right: 15px"
                                class="customer_points_span d-flex justify-content-start align-items-center">{{ @num_format(0) }}</span>
                        </div>
                    </div>
                </div>





            </div>



            <div class="bg-white rounded mt-2 p-2 d-flex flex-column justify-content-between align-items-center"
                style="border-radius: 8px;width: 100%">

                <div class="search-box input-group form-group input-group my-group d-flex justify-content-between"
                    style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    width: 90%;
                                    margin: auto;
                                    width: 100%">
                    <button type="button"
                        style="background-color: #F9C751;
                                                width: fit-content;
                                                height: 100%;
                                                border: none;
                                                border-radius: 16px;
                                                padding: 10px 20px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                color: white
                                                "
                        id="search_button">
                        <i class="fa fa-search"></i>
                    </button>


                    <input type="text" name="search_product" id="search_product" placeholder="@lang('lang.enter_product_name_to_print_labels')"
                        class="form-control ui-autocomplete-input" style="border: none; background-color: transparent"
                        autocomplete="off">
                    @if (isset($weighing_scale_setting['enable']) && $weighing_scale_setting['enable'])
                        <button type="button" class="btn btn-default bg-white btn-flat" id="weighing_scale_btn"
                            data-toggle="modal" data-target="#weighing_scale_modal" title="@lang('lang.weighing_scale')"><i
                                class="fa fa-balance-scale fa-lg"></i></button>
                    @endif


                    <button type="button" class="text-black btn-modal pr-3"
                        style="
                        background-color: transparent;
                        border:none;
                        outline: none;
                        "
                        data-href="{{ action('ProductController@create') }}?quick_add=1"
                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                </div>


            </div>



        </div>


        <div class="col-lg-2" style="max-height: 305px;overflow-y: scroll;overflow-x: hidden;">
            @include('sale_pos.partials.right_side')
        </div>
    </div>

    <div class="p-2 d-flex justify-content-between flex-column-reverse flex-lg-row">

        <div class="col-lg-10 p-1 table-margin">

            <div class="bg-white mb-1 py-2 d-flex flex-column flex-lg-row justify-content-between align-items-start"
                style="border-radius: 8px;width: 100%;min-height: 450px">
                <div class="table-responsive transaction-list">
                    <table class="table table-borderless" id="product_table" style="width: 100%;"
                        class="table table-hover table-striped order-list table-fixed">
                        <thead>
                            <tr style="width: 100%">
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 17% @else 20% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.product')</th>
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 12% @else 20% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.quantity')</th>
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 12% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.price')</th>
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 11% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.discount')</th>
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 10% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.category_discount')</th>
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 9% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.sub_total')</th>
                                @if (session('system_mode') != 'restaurant')
                                    <th class="text-center text-black"
                                        style="width: @if (session('system_mode') != 'restaurant') 9% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                        @lang('lang.current_stock')</th>
                                @endif
                                <th class="text-center text-black"
                                    style="width: @if (session('system_mode') != 'restaurant') 9% @else 15% @endif; font-size: 11px !important; font-weight: 700;">
                                    @lang('lang.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>


            <div class=" py-2" style="border-radius: 8px;background-color: white">

                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mx-auto mb-3"
                    style="width: 80%;">

                    <div
                        class="col-md-6 d-flex align-items-center justify-content-between p-0 text-center font-responsive mb-2 mb-lg-0">
                        <div class="col-2 flex-column d-flex align-items-center text-center mb-2 mb-lg-0">
                            <span class="totals-title mr-2"
                                style="color: #000;font-weight: 600;">{{ __('lang.items') }}</span>
                            <span id="item" class="border border-5 px-2 py-2 rounded">0</span>
                        </div>

                        <div class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                            <span class="totals-title mr-2 "
                                style="color: #000;font-weight: 600;">{{ __('lang.quantity') }}</span>
                            <span id="item-quantity" class="border border-5 px-2 py-2 rounded">0</span>
                        </div>

                        <div class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                            <span class="totals-title mr-2"
                                style="color: #000;font-weight: 600;">{{ __('lang.total') }}</span>
                            <span id="subtotal" class="border border-5 px-1 py-2 rounded">0.00</span>
                        </div>

                        <div class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                            <span class="totals-title mr-2"
                                style="color: #000;font-weight: 600;">{{ __('lang.tax') }}
                            </span>
                            <span id="tax" class="border border-5 px-1 py-2 rounded">0.00</span>
                        </div>

                        <div class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                            <span class="totals-title mr-2"
                                style="color: #000;font-weight: 600;">{{ __('lang.delivery') }}</span>
                            <span id="delivery-cost" class="border border-5 px-1 py-2 rounded">0.00</span>
                        </div>

                    </div>

                    <div class="col-md-6 d-flex align-items-center text-center  mb-2 mb-lg-0">


                        <div class="col-6 font-responsive" style="padding: 0">
                            @php
                                $default_invoice_toc = App\Models\System::getProperty('invoice_terms_and_conditions');
                                if (!empty($default_invoice_toc)) {
                                    $toc_hidden = $default_invoice_toc;
                                } else {
                                    $toc_hidden = array_key_first($tac);
                                }
                            @endphp
                            <input type="hidden" name="terms_and_condition_hidden" id="terms_and_condition_hidden"
                                value="{{ $toc_hidden }}">

                            {!! Form::label('terms_and_condition_id', __('lang.terms_and_conditions'), [
                                'class' => 'label mb-0',
                            ]) !!}
                            <div
                                style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    padding: 4px 6px;
                                    width: 100%;">

                                <select name="terms_and_condition_id" id="terms_and_condition_id" style="width: 100%"
                                    class=" selectpicker terms" data-live-search="true">
                                    <option value="">@lang('lang.please_select')</option>
                                    @foreach ($tac as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="tac_description_div"><span></span></div>

                        <div class="col-6 font-responsive" style="padding: 0;padding-left:2px">
                            {!! Form::label('commissioned_employees', __('lang.commissioned_employees'), ['class' => 'label mb-0']) !!}
                            <div class=""
                                style="background-color: #dedede; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    padding: 4px 6px;
                                    width: 100%;">
                                {!! Form::select('commissioned_employees[]', $employees, false, [
                                    'class' => ' selectpicker terms',
                                    'style' => 'width:100%',
                                    'data-live-search' => 'true',
                                    'multiple',
                                    'id' => 'commissioned_employees',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4 hide shared_commission_div">
                            <div class="i-checks" style="margin-top: 37px;">
                                <input id="shared_commission" name="shared_commission" type="checkbox"
                                    value="1" class="form-control-custom">
                                <label for="shared_commission"><strong>
                                        @lang('lang.shared_commission')
                                    </strong></label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex flex-column flex-lg-row mx-auto mb-1" style="width: 80%">

                    <div class="col-lg-6 d-flex justify-content-between align-items-center mb-1 mb-lg-0">
                        <div class="col-8 d-flex justify-content-between pl-0 height-responsive"
                            style="background-color: white; border: none;
                            border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    width: 100%;">
                            <span class="totals-title  height-responsive pl-2 pl-lg-0 promotion-padding"
                                style="background-color: #21912A;
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;

                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">{{ __('lang.sales_promotion') }}</span>
                            <span id="sales_promotion-cost_span" style="color: #000; padding-right: 30px;width: 45%"
                                class="d-flex justify-content-start align-items-center  height-responsive">0.00</span>
                            <input type="hidden" id="sales_promotion-cost" value="0">
                        </div>

                        <div class="col-4 pl-lg-6 first-head height-responsive" style="width: 100%; ">
                            @if (auth()->user()->can('sp_module.sales_promotion.view') ||
                                    auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
                                    auth()->user()->can('sp_module.sales_promotion.delete'))
                                <button type="button"
                                    style="background-color: #dedede;
                                border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    width: 100%;"
                                    class="height-responsive" data-toggle="modal"
                                    data-target="#discount_modal">@lang('lang.random_discount')</button>
                            @endif
                            {{-- <span id="discount">0.00</span> --}}
                        </div>
                    </div>


                    <div class="col-lg-6 pl-lg-6 first-head height-responsive payment-amount table_room_hide d-flex justify-content-between pl-0 height-responsive"
                        style="background-color: white; border: none;
                            border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb ;
                                    width: 100%;
                                    margin-top: 0">

                        <span class=" height-responsive"
                            style="background-color: #21912A;
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;
                                                padding: 10px 20px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">{{ __('lang.grand_total') }}
                        </span>
                        <span style="color: #000; padding-right: 30px;width: 45%"
                            class="d-flex justify-content-start align-items-center  height-responsive"
                            class="final_total_span">0.00</span>

                    </div>

                </div>




            </div>
        </div>

        <div class="col-lg-2 p-1" style="max-height: 500px;overflow: scroll">

            <div class="col-lg-12 p-1" style="height: 100%">
                <div class="card" style="height: 100%;margin-bottom:0 ">

                    <div class="card-body" style="padding: 0;height: 100%">
                        <div class="col-lg-12 mt-1 table-container" style="height: 100%">
                            <div class="filter-window" style="width: 100% !important; height: 100% !important">

                                <div class="category mt-3" style="height: 100%;width: 100%">
                                    <div class="row ml-2 mr-2 px-2">
                                        <div class="col-7">@lang('lang.choose_category')</div>
                                        <div class="col-5 text-right">
                                            <span class="btn btn-default btn-sm">
                                                <i class="dripicons-cross"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap ml-2 mt-3">
                                        @foreach ($categories as $category)
                                            <div class="col-lg-6 filter-by category-img text-center"
                                                data-id="{{ $category->id }}" data-type="category"
                                                style="height: 100%;">
                                                <img
                                                    src="@if (!empty($category->getFirstMediaUrl('category'))) {{ $category->getFirstMediaUrl('category') }}@else{{ asset('images/default.jpg') }} @endif" />
                                                <p class="text-center">{{ $category->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="sub_category mt-3">
                                    <div class="row ml-2 mr-2 px-2">
                                        <div class="col-7">@lang('lang.choose_sub_category')</div>
                                        <div class="col-5 text-right">
                                            <span class="btn btn-default btn-sm">
                                                <i class="dripicons-cross"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mt-3">
                                        @foreach ($sub_categories as $category)
                                            <div class="col-lg-3 filter-by category-img text-center"
                                                data-id="{{ $category->id }}" data-type="sub_category">
                                                <img
                                                    src="@if (!empty($category->getFirstMediaUrl('category'))) {{ $category->getFirstMediaUrl('category') }}@else{{ asset('images/default.jpg') }} @endif" />
                                                <p class="text-center">{{ $category->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="brand mt-3">
                                    <div class="row ml-2 mr-2 px-2">
                                        <div class="col-7">@lang('lang.choose_brand')</div>
                                        <div class="col-5 text-right">
                                            <span class="btn btn-default btn-sm">
                                                <i class="dripicons-cross"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap ml-2 mt-3">
                                        @foreach ($brands as $brand)
                                            <div class="col-lg-3 filter-by brand-img text-center"
                                                data-id="{{ $brand->id }}" data-type="brand">
                                                <img
                                                    src="@if (!empty($brand->getFirstMediaUrl('brand'))) {{ $brand->getFirstMediaUrl('brand') }}@else{{ asset('images/default.jpg') }} @endif" />
                                                <p class="text-center">{{ $brand->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                            </div>

                            <div class="table-responsive">
                                <table id="filter-product-table" class="table no-shadow product-list"
                                    style="width: 100%; border: 0px;overflow: scroll">
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{--  --}}
    </div>
    {{--  --}}
    <div class="px-4 py-2 mx-3" style="background-color: white;border-radius: 6px;">

        <div class="mx-auto d-flex flex-lg-row flex-column justify-content-between align-items-center font-responsive"
            style="width: 80%;">

            <div style="width: 100%" class="d-flex col-lg-2 flex-lg-column p-0">

                <div class="mb-2 height-responsive" style="width: 100%">
                    <button data-method="cash"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class=" height-responsive font-responsive" data-toggle="modal"
                        data-target="#add-payment" data-backdrop="static" data-keyboard="false" id="cash-btn"><i
                            class="fa fa-money"></i>
                        @lang('lang.pay')</button>
                </div>

                <div class="mb-2 height-responsive ml-1 ml-lg-0" style="width: 100%">
                    <button
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class=" height-responsive font-responsive" id="recent-transaction-btn">
                        <i class="dripicons-clock"></i>
                        @lang('lang.recent_transactions')
                    </button>
                </div>
            </div>

            <div style="width: 100%" class="d-flex col-lg-2 flex-lg-column p-0">

                <div class="mb-2 height-responsive" style="width: 100%">
                    <button data-method="coupon"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive font-responsive" data-toggle="modal"
                        data-target="#coupon_modal" id="coupon-btn"><i class="fa fa-tag"></i>
                        @lang('lang.coupon')</button>
                </div>
                <div class="mb-2 height-responsive ml-1 ml-lg-0" style="width: 100%">
                    <button data-method="online-order"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive font-responsive" id="view-online-order-btn"
                        data-href="{{ action('SellPosController@getOnlineOrderTransactions') }}"><img
                            src="{{ asset('images/online_order.png') }}" style="height: 15px; width: 25px;"
                            alt="icon">
                        @lang('lang.online_orders') <span class="badge badge-danger online-order-badge">0</span>
                    </button>
                </div>

            </div>

            <div style="width: 100%" class="d-flex col-12 col-lg-2 flex-lg-column p-0">

                <div class="mb-2 height-responsive" style="width: 100%">

                    @if (session('system_mode') != 'restaurant')
                        <button data-method="paypal"
                            style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                            type="button" class="height-responsive payment-btn font-responsive" data-toggle="modal"
                            data-target="#add-payment" data-backdrop="static" data-keyboard="false"
                            id="paypal-btn"><i class="fa fa-paypal"></i>
                            @lang('lang.other_online_payments')</button>
                    @endif
                </div>
                <div class="mb-2 height-responsive ml-1 ml-lg-0" style="width: 100%">
                    <button data-method="pay-later"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive font-responsive payment-btn" id="pay-later-btn"><i
                            class="fa fa-hourglass-start"></i>
                        @lang('lang.pay_later')</button>
                </div>
            </div>

            <div style="width: 100%" class="d-flex col-lg-2 flex-lg-column p-0">

                <div class="height-responsive mb-2" style="width: 100%">
                    <button data-method="draft"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" data-toggle="modal" class="height-responsive font-responsive"
                        data-target="#sale_note_modal"class="height-responsive"><i class="dripicons-flag"></i>
                        @lang('lang.draft')</button>
                </div>
                <div class="height-responsive mb-2 ml-1 ml-lg-0" style="width: 100%">
                    <button data-method="draft"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive font-responsive" id="view-draft-btn"
                        data-href="{{ action('SellPosController@getDraftTransactions') }}"><i
                            class="dripicons-flag"></i>
                        @lang('lang.view_draft')</button>
                </div>
            </div>

            <div style="width: 100%" class="d-flex col-lg-2 flex-lg-column p-0">

                <div class="height-responsive mb-2" style="width: 100%">
                    <button
                        style="background-color: #D70007;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive" id="cancel-btn" onclick="return confirmCancel()"><i
                            class="fa fa-close"></i>
                        @lang('lang.cancel')</button>
                </div>
                <div class="height-responsive mb-2 ml-1 ml-lg-0" style="width: 100%">
                    <button data-method="cash"
                        style="background-color: #21912A;border: none;outline: none;box-shadow: 0 8px 6px -5px #bbb ;color: white; border-radius: 6px;width:100%;"
                        type="button" class="height-responsive font-responsive" id="quick-pay-btn"><i
                            class="fa fa-money"></i>
                        @lang('lang.quick_pay')</button>
                </div>

            </div>

        </div>

</section>




<section style="height: 0;padding: 0" class="forms pos-section no-print">
    <div class="container-fluid">
        <div class="row">

            <div class="@if (session('system_mode') == 'pos') col-lg-7 @else col-lg-6 @endif">
                {!! Form::open([
                    'url' => action('SellPosController@store'),
                    'method' => 'post',
                    'files' => true,
                    'class' => 'pos-form',
                    'id' => 'add_pos_form',
                ]) !!}
                <div class="card">
                    <div class="card-body" style="padding: 0px 10px; !important">
                        <input type="hidden" name="default_customer_id" id="default_customer_id"
                            value="@if (!empty($walk_in_customer)) {{ $walk_in_customer->id }} @endif">
                        <input type="hidden" name="row_count" id="row_count" value="0">
                        <input type="hidden" name="customer_size_id_hidden" id="customer_size_id_hidden"
                            value="">
                        <input type="hidden" name="enable_the_table_reservation" id="enable_the_table_reservation"
                            value="{{ App\Models\System::getProperty('enable_the_table_reservation') }}">
                        <div class="row">



                            {{-- <div class="col-lg-12 main_settings">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="col-lg-1" style="padding: 0 !important;">

                                            </div>




                                            @if (session('system_mode') == 'restaurant')
                                                <div class="col-lg-1">
                                                    <button type="button" style="padding: 0px !important;"
                                                        data-href="{{ action('DiningRoomController@getDiningModal') }}"
                                                        data-container="#dining_model"
                                                        class="btn btn-modal pull-right mt-4"><img
                                                            src="{{ asset('images/black-table.jpg') }}" alt="black-table"
                                                            style="width: 40px; height: 33px; margin-top: 7px;"></button>
                                                </div>
                                            @endif




                                        </div>
                                    </div>
                                    <div class="col-lg-12 main_settings">

                                        <div class="row table_room_show hide">
                                            <div class="col-lg-4">
                                                <div class=""
                                                    style="padding: 5px 5px; background:#0082ce; color: #fff; font-size: 20px; font-weight: bold; text-align: center; border-radius: 5px;">
                                                    <span class="room_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for=""
                                                    style="font-size: 20px !important; font-weight: bold; text-align: center; margin-top: 3px;">@lang('lang.table'):
                                                    <span class="table_name"></span></label>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group my-group">
                                                    {!! Form::select('service_fee_id', $service_fees, null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => __('lang.select_service'),
                                                        'id' => 'service_fee_id',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <input type="hidden" name="service_fee_id_hidden" id="service_fee_id_hidden"
                                                value="">
                                            <input type="hidden" name="service_fee_rate" id="service_fee_rate"
                                                value="0">
                                            <input type="hidden" name="service_fee_value" id="service_fee_value"
                                                value="0">
                                        </div>






                                        <div class="row" style="display: none;">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <input type="hidden" id="final_total" name="final_total" />
                                                    <input type="hidden" id="grand_total" name="grand_total" />
                                                    <input type="hidden" id="gift_card_id" name="gift_card_id" />
                                                    <input type="hidden" id="coupon_id" name="coupon_id">
                                                    <input type="hidden" id="total_tax" name="total_tax"
                                                        value="0.00">
                                                    <input type="hidden" id="total_item_tax" name="total_item_tax"
                                                        value="0.00">
                                                    <input type="hidden" id="status" name="status"
                                                        value="final" />
                                                    <input type="hidden" id="total_sp_discount" name="total_sp_discount"
                                                        value="0" />
                                                    <input type="hidden" id="total_pp_discount" name="total_pp_discount"
                                                        value="0" />
                                                    <input type="hidden" name="dining_table_id" id="dining_table_id"
                                                        value="">
                                                    <input type="hidden" name="dining_action_type"
                                                        id="dining_action_type" value="">
                                                </div>
                                            </div>
                                        </div>




                                        <div class="col-lg-12 table_room_show hide"
                                            style="border-top: 2px solid #e4e6fc; margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-lg-8"></div>
                                                <div class="col-lg-4">
                                                    <div class="row">
                                                        <b>@lang('lang.total'): <span class="subtotal">0.00</span></b>
                                                    </div>
                                                    <div class="row">
                                                        <b>@lang('lang.discount'): <span class="discount_span">0.00</span></b>
                                                    </div>
                                                    <div class="row">
                                                        <b>@lang('lang.service'): <span
                                                                class="service_value_span">0.00</span></b>
                                                    </div>
                                                    <div class="row">
                                                        <b>@lang('lang.grand_total'): <span
                                                                class="final_total_span">0.00</span></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pt-4">
                                                <div class="col-lg-8">
                                                    <div class="row">
                                                        <button type="button" name="action" value="print"
                                                            id="dining_table_print" class="btn mr-2 text-white"
                                                            style="background: orange;">@lang('lang.print')</button>
                                                        <button type="button" name="action" value="save"
                                                            id="dining_table_save"
                                                            class="btn mr-2 text-white btn-success">@lang('lang.save')</button>
                                                        <button data-method="cash" style="background: #0082ce"
                                                            type="button" class="btn mr-2 payment-btn text-white"
                                                            data-toggle="modal" data-target="#add-payment"
                                                            data-backdrop="static" data-keyboard="false"
                                                            id="cash-btn">@lang('lang.pay_and_close')</button>
                                                        @if (auth()->user()->can('sp_module.sales_promotion.view') ||
    auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
    auth()->user()->can('sp_module.sales_promotion.delete'))
                                                            <button style="background-color: #d63031" type="button"
                                                                class="btn mr-2 btn-md payment-btn text-white"
                                                                data-toggle="modal"
                                                                data-target="#discount_modal">@lang('lang.random_discount')</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <button style="background-color: #ff00;" type="button"
                                                        class="btn text-white" id="cancel-btn"
                                                        onclick="return confirmCancel()">
                                                        @lang('lang.cancel')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
















                        </div>


                        <div class="payment-options row table_room_hide"
                            style=" width: @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket') 100%; @else 50%; @endif">
                            {{-- <div class="column-5">
                                <button data-method="card" style="background: #0984e3" type="button"
                                    class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment"
                                    id="credit-card-btn"><i class="fa fa-credit-card"></i> @lang('lang.card')</button>
                            </div> --}}



                            {{-- <div class="column-5">
                                <button data-method="cheque" style="background-color: #fd7272" type="button"
                                    class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment"
                                    id="cheque-btn"><i class="fa fa-money"></i> @lang('lang.cheque')</button>
                            </div>
                            <div class="column-5">
                                <button data-method="bank_transfer" style="background-color: #56962b" type="button"
                                    class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment"
                                    id="bank-transfer-btn"><i class="fa fa-building-o"></i>
                                    @lang('lang.bank_transfer')</button>
                            </div> --}}


                            {{-- <div class="column-5">
                                <button data-method="gift_card" style="background-color: #5f27cd" type="button"
                                    class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment"
                                    id="gift-card-btn"><i class="fa fa-credit-card-alt"></i>
                                    @lang('lang.gift_card')</button>
                            </div> --}}
                            {{-- <div class="column-5">
                                <button data-method="deposit" style="background-color: #b33771" type="button"
                                    class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment"
                                    id="deposit-btn"><i class="fa fa-university"></i>
                                    @lang('lang.use_the_balance')</button>
                            </div> --}}

                        </div>
                    </div>

                    @include('sale_pos.partials.payment_modal')
                    @include('sale_pos.partials.discount_modal')
                    {{-- @include('sale_pos.partials.tax_modal') --}}
                    @include('sale_pos.partials.delivery_cost_modal')
                    @include('sale_pos.partials.coupon_modal')
                    @include('sale_pos.partials.contact_details_modal')
                    @include('sale_pos.partials.weighing_scale_modal')
                    @include('sale_pos.partials.non_identifiable_item_modal')
                    @include('sale_pos.partials.customer_sizes_modal')
                    @include('sale_pos.partials.sale_note')

                    {!! Form::close() !!}
                </div>

                <!-- product list -->
                <div class="@if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket') col-lg-5 @else col-lg-6 @endif">



                </div>

                <!-- recent transaction modal -->
                <div id="recentTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog modal-xl" role="document" style="max-width: 65%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('lang.recent_transactions')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">



                                <div class="col-lg-12 modal-filter">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('start_date', __('lang.start_date'), []) !!}
                                                {!! Form::text('start_date', null, ['class' => 'form-control', 'id' => 'rt_start_date']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('end_date', __('lang.end_date'), []) !!}
                                                {!! Form::text('end_date', null, ['class' => 'form-control', 'id' => 'rt_end_date']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('rt_customer_id', __('lang.customer'), []) !!}
                                                {!! Form::select('rt_customer_id', $customers, false, [
                                                    'class' => 'form-control selectpicker',
                                                    'id' => 'rt_customer_id',
                                                    'data-live-search' => 'true',
                                                    'placeholder' => __('lang.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('rt_method', __('lang.payment_type'), []) !!}
                                                {!! Form::select('rt_method', $payment_types, request()->method, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __('lang.all'),
                                                    'data-live-search' => 'true',
                                                    'id' => 'rt_method',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('rt_created_by', __('lang.cashier'), []) !!}
                                                {!! Form::select('rt_created_by', $cashiers, false, [
                                                    'class' => 'form-control selectpicker',
                                                    'id' => 'rt_created_by',
                                                    'data-live-search' => 'true',
                                                    'placeholder' => __('lang.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('rt_deliveryman_id', __('lang.deliveryman'), []) !!}
                                                {!! Form::select('rt_deliveryman_id', $delivery_men, null, [
                                                    'class' => 'form-control sale_filter',
                                                    'placeholder' => __('lang.all'),
                                                    'data-live-search' => 'true',
                                                    'id' => 'rt_deliveryman_id',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    @include('sale_pos.partials.recent_transactions')
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!-- draft transaction modal -->
                <div id="draftTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog" role="document" style="width: 65%">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('lang.draft_transactions')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-lg-12 modal-filter">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('draft_start_date', __('lang.start_date'), []) !!}
                                                {!! Form::text('start_date', null, ['class' => 'form-control', 'id' => 'draft_start_date']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('draft_end_date', __('lang.end_date'), []) !!}
                                                {!! Form::text('end_date', null, ['class' => 'form-control', 'id' => 'draft_end_date']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('draft_deliveryman_id', __('lang.deliveryman'), []) !!}
                                                {!! Form::select('draft_deliveryman_id', $delivery_men, null, [
                                                    'class' => 'form-control sale_filter',
                                                    'placeholder' => __('lang.all'),
                                                    'data-live-search' => 'true',
                                                    'id' => 'draft_deliveryman_id',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    @include('sale_pos.partials.view_draft')
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!-- onlineOrder transaction modal -->
                <div id="onlineOrderTransaction" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal text-left">

                    <div class="modal-dialog" role="document" style="width: 65%">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('lang.online_order_transactions')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-lg-12 modal-filter">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('online_order_start_date', __('lang.start_date'), []) !!}
                                                {!! Form::text('start_date', null, ['class' => 'form-control', 'id' => 'online_order_start_date']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                {!! Form::label('online_order_end_date', __('lang.end_date'), []) !!}
                                                {!! Form::text('end_date', null, ['class' => 'form-control', 'id' => 'online_order_end_date']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    @include('sale_pos.partials.view_online_order')
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <div id="dining_model" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">
                </div>
                <div id="dining_table_action_modal" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal fade text-left">
                </div>
            </div>
        </div>


</section>


<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection
@section('style')
<style>
    .red {
        color: #a50d0d !important;
    }
</style>
@endsection
@section('javascript')
<script src="{{ asset('js/onscan.min.js') }}"></script>
<script src="{{ asset('js/pos.js') }}"></script>
<script src="{{ asset('js/dining_table.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.online-order-badge').hide();
    })
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('order-channel');
    channel.bind('new-order', function(data) {
        if (data) {
            let badge_count = parseInt($('.online-order-badge').text()) + 1;
            $('.online-order-badge').text(badge_count);
            $('.online-order-badge').show();
            var transaction_id = data.transaction_id;
            $.ajax({
                method: 'get',
                url: '/pos/get-transaction-details/' + transaction_id,
                data: {},
                success: function(result) {
                    toastr.success(LANG.new_order_placed_invoice_no + ' ' + result.invoice_no);
                    let notification_number = parseInt($('.notification-number').text());
                    console.log(notification_number, 'notification-number');
                    if (!isNaN(notification_number)) {
                        notification_number = parseInt(notification_number) + 1;
                    } else {
                        notification_number = 1;
                    }
                    $('.notification-list').empty().append(
                        `<i class="dripicons-bell"></i><span class="badge badge-danger notification-number">${notification_number}</span>`
                    );
                    $('.notifications').prepend(
                        `<li>
                                <a class="pending notification_item"
                                    data-mark-read-action=""
                                    data-href="{{ url('/') }}/pos/${transaction_id}/edit?status=final">
                                    <p style="margin:0px"><i class="dripicons-bell"></i> ${LANG.new_order_placed_invoice_no} #
                                        ${result.invoice_no}</p>
                                    <span class="text-muted">
                                        @lang('lang.total'): ${__currency_trans_from_en(result.final_total, false)}
                                    </span>
                                </a>

                            </li>`
                    );
                    $('.no_new_notification_div').addClass('hide');

                },
            });
        }
    });
</script>
@endsection
