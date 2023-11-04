@extends('layouts.app')
@section('title', __('lang.category_report'))

@section('content')
    <div class="col-md-12  no-print">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="print-title">@lang('lang.product_report')</h3>
            </div>
           
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('lang.category')</th>
                                    <th>@lang('lang.sales')</th>
                                    <th>@lang('lang.purchases')</th>
                                    <th>@lang('lang.purchased_qty')</th>
                                    <th>@lang('lang.sold_qty')</th>
                                    <th>@lang('lang.profit')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->product_class_name }}</td>
                                        <td>{{ number_format($transaction->sold_amount,3) }}</td>
                                        <td>{{ number_format($transaction->purchased_amount,3) }}</td>
                                        <td>{{ number_format($transaction->purchased_qty,3) }}</td>
                                        <td>{{ number_format($transaction->sold_qty,3) }}</td>
                                        <td> {{ number_format($transaction->sold_amount - $transaction->purchased_amount,3) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th style="text-align: right">@lang('lang.total')</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection
