<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('TransactionPaymentController@store'), 'method' => 'post', 'id' => 'add_payment_form', 'enctype' => 'multipart/form-data']) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang('lang.add_payment')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('amount', __('lang.amount') . ':*', []) !!} <br>
                        @if (isset($transaction->return_parent))
                        {!! Form::text('amount', @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount') - $transaction->return_parent->final_total), ['class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        @else 
                        {!! Form::text('amount', @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount')), ['class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        @endif
                       
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('method', __('lang.payment_type') . ':*', []) !!}
                        {!! Form::select('method', $payment_type_array, 'cash', ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'required', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('paid_on', __('lang.payment_date') . ':', []) !!} <br>
                        {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control datepicker', 'readonly', 'required', 'placeholder' => __('lang.payment_date')]) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('upload_documents', __('lang.upload_documents') . ':', []) !!} <br>
                        <input type="file" name="upload_documents[]" id="upload_documents" multiple>
                    </div>
                </div>
                <div class="col-md-4 not_cash_fields card_field hide">
                    <div class="form-group">
                        {!! Form::label('ref_number', __('lang.ref_number') . ':', []) !!} <br>
                        {!! Form::text('ref_number', null, ['class' => 'form-control not_cash', 'placeholder' => __('lang.ref_number')]) !!}
                    </div>
                </div>
                <div class="col-md-4 not_cash_fields hide">
                    <div class="form-group">
                        {!! Form::label('bank_deposit_date', __('lang.bank_deposit_date') . ':', []) !!} <br>
                        {!! Form::text('bank_deposit_date', @format_date(date('Y-m-d')), ['class' => 'form-control not_cash datepicker', 'readonly', 'placeholder' => __('lang.bank_deposit_date')]) !!}
                    </div>
                </div>
                <div class="col-md-4 not_cash_fields hide">
                    <div class="form-group">
                        {!! Form::label('bank_name', __('lang.bank_name') . ':', []) !!} <br>
                        {!! Form::text('bank_name', null, ['class' => 'form-control not_cash', 'placeholder' => __('lang.bank_name')]) !!}
                    </div>
                </div>

                <div class="col-md-4 card_field hide">
                    <label>@lang('lang.card_number') *</label>
                    <input type="text" name="card_number" class="form-control">
                </div>
                <div class="col-md-2 card_field hide">
                    <label>@lang('lang.month')</label>
                    <input type="text" name="card_month" class="form-control">
                </div>
                <div class="col-md-2 card_field hide">
                    <label>@lang('lang.year')</label>
                    <input type="text" name="card_year" class="form-control">
                </div>
                @if ($transaction->type == 'add_stock')
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('source_type', __('lang.source_type'), []) !!} <br>
                            {!! Form::select('source_type', ['user' => __('lang.user'), 'pos' => __('lang.pos'), 'store' => __('lang.store'), 'safe' => __('lang.safe')], 'user', ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('source_of_payment', __('lang.source_of_payment'), []) !!} <br>
                            {!! Form::select('source_id', $users, null, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select'), 'id' => 'source_id']) !!}
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $('#source_type').change(function() {
        if ($(this).val() !== '') {
            $.ajax({
                method: 'get',
                url: '/add-stock/get-source-by-type-dropdown/' + $(this).val(),
                data: {},
                success: function(result) {
                    $("#source_id").empty().append(result);
                    $("#source_id").selectpicker("refresh");
                },
            });
        }
    });
    $('.selectpicker').selectpicker('refresh');
    $('.datepicker').datepicker({
        language: '{{ session('language') }}',
        todayHighlight: true,
    });
    $('#add_payment_form #method').change(function() {
        var method = $(this).val();

        if (method === 'card') {
            $('.not_cash_fields').addClass('hide');
            $('.card_field').removeClass('hide');
            $('.not_cash').attr('required', false);
        } else if (method === 'cash') {
            $('.not_cash_fields').addClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', false);
        } else {
            $('.not_cash_fields').removeClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', false);
        }
    })
</script>
