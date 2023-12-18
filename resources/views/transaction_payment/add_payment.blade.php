<div class="modal-dialog" id="payment_modal" role="document">
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
                        {{-- @if($balance >0 && $balance<$transaction->final_total - $transaction->transaction_payments->sum('amount'))
                        @if (isset($transaction->return_parent)) --}}
                        {!! Form::text('amount', @num_format($amount), ['id' => 'amount_pay','class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        {{-- @else 
                        {!! Form::text('amount', @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount')-$balance), ['class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        @endif 
                         @else 
                        @if (isset($transaction->return_parent))
                        
                        {!! Form::text('amount', @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount') - $transaction->return_parent->final_total), ['class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        @else 
                        {!! Form::text('amount', @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount')), ['class' => 'form-control', 'placeholder' => __('lang.amount')]) !!}
                        @endif 
                        @endif --}}
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
                <div class="col-md-6 mt-1">
                    <label class="change_text">@lang('lang.change'): </label>
                    <span class="change" class="ml-2">0.00</span>
                    <div class="col-md-6">
                        <button type="button" 
                            class="ml-1 btn btn-danger add_to_customer_balance hide">@lang('lang.add_to_customer_balance')</button>
                        <input type="hidden" name="add_to_customer_balance" id="add_to_customer_balance" class="add_to_customer_balance_in">
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
            <button type="button" id="submit_form_button" class="btn btn-primary">@lang('lang.save')</button>
            <button type="button"id="close_modal_button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).ready(function() {
        var pageTitle = window.location.pathname;
        console.log(pageTitle);
      
        $('#submit_form_button').click(function() {
            $('#add_payment_form').submit();
        });

        if(pageTitle!=="/pos/create"){
        var updateadd_payment_formClicked = false;
        $('#add_payment_form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData($(this)[0]);
            let submitButton = $("#submit_form_button"); 
            if (!updateadd_payment_formClicked) {
                console.log('dae')
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response here
                        console.log(response);
                    
                        $('#add_payment_form')[0].reset();
                        $('#close_modal_button').click();
                        $('#sales_table').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        // Handle error response here
                        console.log(error);
                    }
                });
                updateadd_payment_formClicked = true;

                // Disable the button after it has been clicked
                submitButton.prop('disabled', true);
            }
        });
        }
        var inputValue = $('#amount_pay').val();
        console.log(inputValue);
        if (inputValue !== undefined) {
            var initialAmount = parseFloat(inputValue.replace(',', ''));
            // Rest of your code using initialAmount
        } else {
            console.error("Error: The element with id 'amount_pay' does not exist or is undefined.");
        }
        // Store the initial amount value
        // var initialAmount = parseFloat($('#amount_pay').val().replace(',', '')); // Assuming 'num_format' formats the number as a string with commas

        $('#amount_pay').on('change', function () {
            var newAmount = parseFloat($(this).val().replace(',', ''));

            if (!isNaN(newAmount) && newAmount > initialAmount) {
                var change = Math.abs(newAmount - initialAmount);
                $(".add_to_customer_balance").removeClass("hide");
                $('.change').text(change.toFixed(2));
                $(document).on("click", ".add_to_customer_balance", function () {
                    $('.change').text(change.toFixed(2)); // Update the change value
                    
                    // if ($('.payment_way').val() !== 'deposit') {
                        $('.add_to_customer_balance_in').val(change.toFixed(2));
                        console.log($('#add_to_customer_balance').val());
                        // $('.change_amount').val(0);
                        // $(this).attr('disabled', true);

                        // Assuming you have a 'received_amount' variable
                        var newReceivedAmount = newAmount - change;
                        $('#amount_pay').val(newReceivedAmount.toFixed(2));
                });
                $(document).on("click", ".close , #close_modal_button", function () {
                    $('.add_to_customer_balance').addClass('hide');
                    $('.add_to_customer_balance_in').val('0');
                    console.log($('#add_to_customer_balance').val());
                });
                // } else {
                //     $('.add_to_customer_balance').addClass('hide');
                // }
            }
        });
    });
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
