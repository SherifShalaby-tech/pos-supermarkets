<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('TransactionPaymentController@payCustomerDue', $customer->id), 'method' =>
        'post', 'id' => 'pay_customer_due_form' ])
        !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.pay_customer_due' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" value="{{$extract_due}}" name="extract_due"/>
                            <label for="">@lang('lang.customer_name'): {{$customer->name}}</label> <br>
                            <label for="">@lang('lang.mobile'): {{$customer->mobile}}</label><br>
                            <label for="">@lang('lang.address'): {{$customer->address}}</label><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('amount', __('lang.amount'). ':*', []) !!} <br>
                            {!! Form::text('amount', @num_format($due), ['class' => 'form-control', 'placeholder'
                            => __('lang.amount')]) !!}
                            <input type="hidden" value="{{@num_format($due)}}" name="balance"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('method', __('lang.payment_type'). ':*', []) !!}
                            {!! Form::select('method', $payment_type_array,
                            null, ['class' => 'selectpicker form-control',
                            'data-live-search'=>"true", 'required',
                            'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('paid_on', __('lang.payment_date'). ':', []) !!} <br>
                            {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control datepicker', 'readonly',
                            'placeholder' => __('lang.payment_date')]) !!}
                        </div>
                    </div>
                    
                    <div class="col-md-6 mt-1">
                        <label class="change_text">@lang('lang.change'): </label>
                        <spand class="change" class="ml-2">0.00</spand>
                        <div class="col-md-6">
                            <button type="button" 
                                class="ml-1 btn btn-danger add_to_customer_balance hide">@lang('lang.add_to_customer_balance')</button>
                            <input type="hidden" name="add_to_customer_balance" id="add_to_customer_balance" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('upload_documents', __('lang.upload_documents'). ':', []) !!} <br>
                            {!! Form::file('upload_documents[]', null, ['class' => '']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('ref_number', __('lang.ref_number'). ':', []) !!} <br>
                            {!! Form::text('ref_number', null, ['class' => 'form-control not_cash',
                            'placeholder' => __('lang.ref_number')]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('bank_deposit_date', __('lang.bank_deposit_date'). ':', []) !!} <br>
                            {!! Form::text('bank_deposit_date', null, ['class' => 'form-control not_cash datepicker',
                            'readonly',
                            'placeholder' => __('lang.bank_deposit_date')]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('bank_name', __('lang.bank_name'). ':', []) !!} <br>
                            {!! Form::text('bank_name', null, ['class' => 'form-control not_cash',
                            'placeholder' => __('lang.bank_name')]) !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" id="pay_due" class="btn btn-primary pay_due">@lang( 'lang.save' )</button>
            <button type="button" id="close_modal_button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>

    $('.selectpicker').selectpicker('refresh');
    $('.datepicker').datepicker({
        language: '{{session('language')}}',
        todayHighlight: true,
    });
    $('#method').change(function(){
        var method = $(this).val();

        if(method === 'cash'){
            $('.not_cash_fields').addClass('hide');
            $('.not_cash').attr('required', false);
        }else{
            $('.not_cash_fields').removeClass('hide');
            $('.not_cash').attr('required', true);
        }
    })
    $(document).ready(function() {
        // Store the initial amount value
     var initialAmount = parseFloat($('#amount').val().replace(',', '')); // Assuming 'num_format' formats the number as a string with commas

$('#amount').on('change', function () {
        var newAmount = parseFloat($(this).val().replace(',', ''));

        if (!isNaN(newAmount) && newAmount > initialAmount) {
                var change = Math.abs(newAmount - initialAmount);
                $(".add_to_customer_balance").removeClass("hide");
                $('.change').text(change.toFixed(2));
                $(document).on("click", ".add_to_customer_balance", function () {
                    $('.change').text(change.toFixed(2)); // Update the change value
                    
                    // if ($('.payment_way').val() !== 'deposit') {
                        $('#add_to_customer_balance').val(change.toFixed(2));
                        console.log($('#add_to_customer_balance').val());
                        // $('.change_amount').val(0);
                        // $(this).attr('disabled', true);

                        // Assuming you have a 'received_amount' variable
                        var newReceivedAmount = newAmount - change;
                        $('#amount').val(newReceivedAmount.toFixed(2));
                });
                $(document).on("click", ".close , #close_modal_button", function () {
                    $('.add_to_customer_balance').addClass('hide');
                    $('#add_to_customer_balance').val(' ');
                    console.log($('#add_to_customer_balance').val());
                });
                // } else {
                //     $('.add_to_customer_balance').addClass('hide');
                // }
            }
        });
    });
</script>
<script src="{{ asset('js/due.js') }}"></script>
