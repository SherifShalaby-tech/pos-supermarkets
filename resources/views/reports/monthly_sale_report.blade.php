@extends('layouts.app')
@section('title', __('lang.monthly_sale_and_purchase_report'))
@section('styles')
<style>
    .months td{
        border-bottom: 2px solid rgb(241, 89, 89);
    }
    .sale-row td{
        border-top: 2px solid rgb(241, 89, 89);
        border-bottom: 2px solid rgb(241, 89, 89);
    }
    .sale-row td:first-child {
    border-left: 2px solid rgb(241, 89, 89);
    }
    .sale-row td:last-child {
    border-right: 2px solid rgb(241, 89, 89);
    }
    .purchase-row td{
        border-top: 2px solid rgb(84, 235, 177);
        border-bottom: 2px solid rgb(84, 235, 177);
    }
    .purchase-row td:first-child {
    border-left: 2px solid rgb(84, 235, 177);
    }
    .purchase-row td:last-child {
    border-right: 2px solid rgb(84, 235, 177);
    }
</style>
@endsection
@section('content')
<div class="col-md-12  no-print">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4>@lang('lang.monthly_sale_and_purchase_report')</h4>
        </div>
        @if(session('user.is_superadmin'))
        <form action="">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('store_id', __('lang.store'), []) !!}
                            {!! Form::select('store_id', $stores, request()->store_id, ['class' =>
                            'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <br>
                        <button type="submit" class="btn btn-success mt-2">@lang('lang.filter')</button>
                        <a href="{{action('ReportController@getMonthlySaleReport')}}"
                            class="btn btn-danger mt-2 ml-2">@lang('lang.clear_filter')</a>
                    </div>
                </div>
            </div>
        </form>
        @endif
        <div class="card-body">
            <div class="col-md-12">
                <table class="table table-bordered"
                    style="border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                    <thead>
                        @php
                        $next_year = $year + 1;
                        $pre_year = $year - 1;
                        @endphp
                        <tr>
                            <th></th>
                            <th><a href="{{url('report/get-monthly-sale-report?year='.$pre_year)}}"><i
                                        class="fa fa-arrow-left"></i> {{trans('lang.previous')}}</a></th>
                            <th colspan="10" class="text-center">{{$year}}</th>
                            <th><a href="{{url('report/get-monthly-sale-report?year='.$next_year)}}">{{trans('lang.next')}}
                                    <i class="fa fa-arrow-right"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="months">
                            <td></td>
                            <td><strong>@lang('lang.January')</strong></td>
                            <td><strong>@lang('lang.February')</strong></td>
                            <td><strong>@lang('lang.March')</strong></td>
                            <td><strong>@lang('lang.April')</strong></td>
                            <td><strong>@lang('lang.May')</strong></td>
                            <td><strong>@lang('lang.June')</strong></td>
                            <td><strong>@lang('lang.July')</strong></td>
                            <td><strong>@lang('lang.August')</strong></td>
                            <td><strong>@lang('lang.September')</strong></td>
                            <td><strong>@lang('lang.October')</strong></td>
                            <td><strong>@lang('lang.November')</strong></td>
                            <td><strong>@lang('lang.December')</strong></td>
                        </tr>
                        <tr  class="sale-row">
                            <td><h5>@lang('lang.sales')</h5></td>
                            @foreach($total_discount_sell as $key => $discount)
                            <td>
                                @if($discount > 0)
                                <strong>{{trans("lang.product_discount")}}</strong><br>
                                <span>{{@num_format($discount)}}</span><br><br>
                                @endif
                                @if($total_tax_sell[$key] > 0)
                                <strong>{{trans("lang.product_tax")}}</strong><br>
                                <span>{{isset($total_tax_sell)?@num_format($total_tax_sell[$key]):0}}</span><br><br>
                                @endif
                                @if($shipping_cost_sell[$key] > 0)
                                <strong>{{trans("lang.delivery_cost")}}</strong><br>
                                <span>{{@num_format($shipping_cost_sell[$key])}}</span><br><br>
                                @endif
                                @if($total_sell[$key] > 0)
                                <strong>{{trans("lang.grand_total")}}</strong><br>
                                <span>{{@num_format($total_sell[$key])}}</span><br>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        <tr class="purchase-row">
                            <td><h5>@lang('lang.purchases')</h5></td>
                            @foreach($total_discount_addstock as $key => $discount)
                            <td>
                                @if($discount > 0)
                                <strong>{{trans("lang.product_discount")}}</strong><br>
                                <span>{{@num_format($discount)}}</span><br><br>
                                @endif
                                @if($total_tax_addstock[$key] > 0)
                                <strong>{{trans("lang.product_tax")}}</strong><br>
                                <span>{{@num_format($total_tax_addstock[$key])}}</span><br><br>
                                @endif
                                @if($shipping_cost_addstock[$key] > 0)
                                <strong>{{trans("lang.delivery_cost")}}</strong><br>
                                <span>{{@num_format($shipping_cost_addstock[$key])}}</span><br><br>
                                @endif
                                @if($total_addstock[$key] > 0)
                                <strong>{{trans("lang.grand_total")}}</strong><br>
                                <span>{{@num_format($total_addstock[$key])}}</span><br>
                                {{-- <span>{{@num_format($total_p[$key])}}</span><br> --}}
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($total_net_profit as $key => $net_profit)
                            <td>
                                <strong>{{trans("lang.wins")}}</strong><br>
                                <strong>{{@num_format($net_profit)}}</strong>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection
