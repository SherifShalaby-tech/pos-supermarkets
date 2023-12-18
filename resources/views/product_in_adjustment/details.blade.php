@extends('layouts.app')
@section('title', __('lang.product_in_adjustment'))

@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.product_in_adjustment')</h4>
                    </div>

                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th style="">@lang('lang.name')</th>
                                <th>@lang('lang.product_code')</th>
                                <th >@lang('lang.old_stock')</th>
                                <th>@lang('lang.new_stock')</th>
                                <th>@lang('lang.shortage')</th>
                                <th>@lang('lang.value_of_shortage')</th>
                                <th>@lang('lang.old_purchase_price')</th>
                                <th>@lang('lang.new_purchase_price')</th>
                                <th>@lang('lang.old_sell_price')</th>
                                <th>@lang('lang.new_sell_price')</th>
                            </tr>
                        </thead>
                    <tbody>
                        @foreach ($adjustment_details as $adjustment)
                        <tr>
                            <td>{{($adjustment->product->name)}}</td>
                            <td>{{$adjustment->product->sku}}</td>
                            <td>{{$adjustment->old_stock ?? '-'}}</td>
                            <td>{{$adjustment->new_stock ?? '-'}}</td>
                            <td>{{$adjustment->shortage ?? '-'}}</td>
                            <td>{{@num_format($adjustment->shortage_value )?? '-'}}</td>
                            <td>{{$adjustment->old_purchase_price ?? '-'}}</td>
                            <td>{{$adjustment->new_purchase_price ?? '-'}}</td>
                            <td>{{$adjustment->old_sell_price ?? '-'}}</td>
                            <td>{{$adjustment->new_sell_price ?? '-'}}</td>
                        </tr>

                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <th></th>
                            <td></td>
                            <th></th>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">

</script>
@endsection
