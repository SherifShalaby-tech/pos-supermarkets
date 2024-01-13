<div class="modal-dialog" role="document" >
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="modal-title">@lang('lang.total_latest_payments')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="item">
                <div class="col-md-12">
                    <div class="table-responsive no-print">
                        <table id="cashes_table" class="table" style="min-height: 300px;">
                            <thead>
                                <tr>
                                    <th>@lang('lang.date_and_time')</th>
                                    <th>@lang('lang.reference')</th>
                                    <th>@lang('lang.store')</th>
                                    <th>@lang('lang.customer')</th>
                                    <th>@lang('lang.sale_status')</th>
                                    <th>@lang('lang.payment_status')</th>
                                    <th class="sum">@lang('lang.grand_total')</th>
                                    <th class="sum">@lang('lang.paid')</th>
                                    <th class="sum">@lang('lang.recieved_amount')</th>
                                    <th class="sum">@lang('lang.due_sale_list')</th>
                                    <th>@lang('lang.payment_date')</th>
                                    <th>@lang('lang.cashier')</th>
                                    <th class="notexport">@lang('lang.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $item)
                                <tr>
                                    <td>{{ @format_date($item->created_at) }}</td>
                                    <td>{{ $item->invoice_no ?? '' }}</td>
                                    <td>{{ !empty($item->store) ? $item->store->name ?? '' : '' }}</td>
                                    <td>{{ !empty($item->customer) ? $item->customer->name ?? '' : '' }}</td>
                                    <td>{{ $item->payment_status }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ @number_format($item->final_total) }}</td>
                                    <td>
                                        @php
                                            $amount_paid = 0;
                                            if (!empty($item->transaction_payments)) {
                                                $payments = $item->transaction_payments;
                                            }
                                            foreach ($payments as $payment) {
                                                $amount_paid += $payment->amount;
                                            }
                                        @endphp
                                        {{ @number_format($amount_paid) }}
                                    </td>
                                    <td>@php
                                        $recieved_amount = \App\Models\CashRegisterTransaction::where('cash_register_id', $id)
                                            ->where('transaction_id', $item->id)
                                            ->first()->amount;
                                    @endphp
                                        {{ @number_format($recieved_amount) }}
                                    </td>
                                    <td>
                                        @php
                                            $paid = $item->transaction_payments->sum('amount');
                                        @endphp
                                        {{ $item->final_total - $paid }}
                                    </td>
                                    <td>{{ @format_date($item->transaction_date) }}</td>
                                    <td>
                                        {{ !empty($item->created_by_user) ? $item->created_by_user->name ?? '' : '' }}
                                    </td>
                                    <td>
                                        <a data-href="{{ action('SellController@show', $item->id) }}"
                                            data-container=".view_modal" class="btn btn-modal"><i class="fa fa-eye"></i>
                                            {{ __('lang.view') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).ready(function () {
        $('#cashes_table').dataTable({});    
    });
</script>
