<!-- payment modal -->
<div id="add-payment" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 id="exampleModalLabel" class="modal-title position-relative">@lang('lang.finalize_sale')
                    <span class="position-absolute header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white close-payment-madal">
                    <span aria-hidden="true">
                        <i class="dripicons-cross"></i>
                    </span>
                </button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 customer_name_div hide">
                        <label for="" style="font-weight: bold">@lang('lang.customer'): <span
                                class="customer_name"></span></label>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div id="payment_rows" class="col-md-12 mb-3">
                                <div class="payment_row d-flex flex-column position-relative">

                                    <div
                                        class="d-flex justify-content-between align-items-center  @if (app()->isLocale('ar')) flex-row-reverse mb-2 @else flex-row @endif">
                                        <div class="col-md-7 px-4">
                                            <label
                                                class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.received_amount')
                                                *</label>
                                            <input type="text" name="payments[0][amount]"
                                                class="modal-input numkey received_amount" required id="amount"
                                                step="any">
                                        </div>
                                        <div
                                            class="col-md-5 position-relative px-4 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse mt-4 @else flex-row @endif">

                                            <label
                                                class="change_text d-flex justify-content-center align-items-center col-md-6 modal-g-label mb-0">@lang('lang.change')
                                            </label>
                                            <span class="change  col-md-6 modal-g-value">0.00</span>
                                            <span class="position-absolute madal-gray-bg"></span>
                                            <input type="hidden" name="payments[0][change_amount]"
                                                class="change_amount" id="change_amount">
                                            <input type="hidden" name="payments[0][pending_amount]"
                                                class="pending_amount">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button"
                                            class="ml-1 btn btn-danger add_to_customer_balance hide">@lang('lang.add_to_customer_balance')</button>
                                        <input type="hidden" name="add_to_customer_balance"
                                            id="add_to_customer_balance" value="0">
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center  @if (app()->isLocale('ar')) flex-row-reverse mb-2 @else flex-row @endif">
                                        <div class="col-md-7 px-4">
                                            <label
                                                class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.payment_method')
                                                *</label>
                                            {!! Form::select('payments[0][method]', $payment_types, null, [
                                                'class' => 'modal-input method payment_way',
                                                'required',
                                            ]) !!}
                                        </div>
                                        <div
                                            class="col-md-5 position-relative px-4 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse mt-4 @else flex-row @endif">
                                            <label
                                                class="surplus_lable  d-flex justify-content-center align-items-center col-md-6 modal-g-label mb-0">@lang('lang.surplus')</label>
                                            <span class="payment_modal_surplus_text col-md-6 modal-g-value"></span>
                                            <span class="position-absolute madal-gray-bg"></span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center  @if (app()->isLocale('ar')) flex-row-reverse mb-2 @else flex-row @endif">
                                        <div class="col-md-7 px-4">
                                            <label
                                                class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.paying_amount')
                                                *</label>
                                            <input type="text" name="payments[0][paying_amount]"
                                                class="modal-input numkey" id="paying_amount" step="any">
                                        </div>

                                        <div
                                            class="col-md-5 position-relative px-4 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse mt-4 @else flex-row @endif">
                                            <label
                                                class="discount_lable  d-flex justify-content-center align-items-center col-md-6 modal-g-label mb-0">@lang('lang.discount')</label>
                                            <span class="payment_modal_discount_text   col-md-6 modal-g-value"></span>
                                            <span class="position-absolute madal-gray-bg"></span>
                                        </div>
                                    </div>


                                    <span class="position-absolute modal-border"></span>

                                    {{-- -------- hidden -------- --}}
                                    <div class="form-group col-md-12 mt-3 hide card_field">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>@lang('lang.card_number') *</label>
                                                <input type="text" name="payments[0][card_number]"
                                                    class="form-control">
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label>@lang('lang.card_security')</label>
                                                <input type="text" name="payments[0][card_security]"
                                                    class="form-control">
                                            </div> --}}
                                            <div class="col-md-2">
                                                <label>@lang('lang.month')</label>
                                                <input type="text" name="payments[0][card_month]"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <label>@lang('lang.year')</label>
                                                <input type="text" name="payments[0][card_year]"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 bank_field hide">
                                        <label>@lang('lang.bank_name')</label>
                                        <input type="text" name="payments[0][bank_name]" class="form-control">
                                    </div>

                                    <div class="form-group col-md-12 card_bank_field hide">
                                        <label>@lang('lang.ref_number') </label>
                                        <input type="text" name="payments[0][ref_number]" class="form-control">
                                    </div>

                                    <div class="form-group col-md-12 cheque_field hide">
                                        <label>@lang('lang.cheque_number')</label>
                                        <input type="text" name="payments[0][cheque_number]" class="form-control">
                                    </div>
                                    {{-- -------- hidden -------- --}}
                                </div>

                            </div>

                            <h4 class="col-md-12 mt-3 @if (app()->isLocale('ar')) d-block text-end @endif">
                                @lang('lang.quick_cash')
                            </h4>
                            <div class="col-md-12 mb-3 d-flex position-relative  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif qc"
                                data-initial="1">
                                <div
                                    class="col-md-11  d-flex  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn" data-amount="10"
                                            type="button">10</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn" data-amount="20"
                                            type="button">20</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn" data-amount="50"
                                            type="button">50</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn" data-amount="100"
                                            type="button">100</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn" data-amount="500"
                                            type="button">500</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-main py-1 qc-btn sound-btn"
                                            data-amount="1000" type="button">1000</button>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-block btn-danger py-1 qc-btn sound-btn" data-amount="0"
                                        type="button">
                                        <i class="fa fa-arrow-rotate-left"></i>
                                    </button>
                                </div>
                                <span class="position-absolute modal-buttons-border"></span>
                            </div>

                            <div class="col-md-12 mt-3 btn-add-payment">
                                <button type="button" id="add_payment_row"
                                    class="btn border-r6 btn-main  btn-block">
                                    @lang('lang.add_payment_row')</button>
                            </div>

                            <div class="col-md-6 deposit-fields hide">
                                <h6 class="bg-success" style="color: #fff; padding: 10px 15px;">
                                    @lang('lang.current_balance'): <span class="current_deposit_balance"></span> <br>
                                    <span class="hide balance_error_msg"
                                        style="color: red; font-size: 12px">@lang('lang.customer_not_have_sufficient_balance')</span>
                                </h6>
                                <input type="hidden" name="current_deposit_balance" id="current_deposit_balance"
                                    value="0">
                            </div>

                            <div class="col-md-12 deposit-fields hide">
                                <div class="row">
                                    <div class="col-md-2">
                                        <button type="button"
                                            class="ml-1 btn btn-success use_it_deposit_balance">@lang('lang.use_it')</button>
                                        <input type="hidden" name="used_deposit_balance" id="used_deposit_balance"
                                            value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="bg-success" style="color: #fff; padding: 10px 15px;">
                                            @lang('lang.remaining_balance'): <span class="remaining_balance_text"></span> </h6>
                                        <input type="hidden" name="remaining_deposit_balance"
                                            id="remaining_deposit_balance" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button"
                                            class="ml-1 btn btn-danger add_to_deposit">@lang('lang.add_to_deposit')</button>
                                        <input type="hidden" name="add_to_deposit" id="add_to_deposit"
                                            value="0">
                                    </div>
                                </div>
                            </div>

                            @php
                                $show_the_window_printing_prompt = App\Models\System::getProperty('show_the_window_printing_prompt');
                            @endphp


                            <div class="form-group col-md-12 gift_card_field hide">
                                <div class="col-md-12">
                                    <label>@lang('lang.gift_card_number') *</label>
                                    <input type="text" name="gift_card_number" id="gift_card_number"
                                        class="form-control" placeholder="@lang('lang.enter_gift_card_number')">
                                    <span class="gift_card_error" style="color: red;"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label><b>@lang('lang.current_balance'):</b> </label><br>
                                        <span class="gift_card_current_balance"></span>
                                        <input type="hidden" name="gift_card_current_balance"
                                            id="gift_card_current_balance">
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('lang.enter_amount_to_be_used') </label>
                                        <input type="text" name="amount_to_be_used" id="amount_to_be_used"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('lang.remaining_balance') </label>
                                        <input type="text" name="remaining_balance" id="remaining_balance"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label><b>@lang('lang.final_total'):</b> </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="gift_card_final_total" id="gift_card_final_total"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 px-4">
                                <label
                                    class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.payment_note')</label>
                                <textarea id="payment_note" rows="2" class="form-control" name="payment_note"></textarea>
                            </div>
                            <div class="col-md-12 px-4 form-group">
                                <label
                                    class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.sale_note')</label>
                                <textarea rows="3" class="form-control" name="sale_note" id="sale_note">{{ !empty($transaction) ? $transaction->sale_note : '' }}</textarea>
                            </div>
                            <div class="col-md-12 px-4 form-group">
                                <label
                                    class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">@lang('lang.staff_note')</label>
                                <textarea rows="3" class="form-control staff_note" name="staff_note">{{ !empty($transaction) ? $transaction->staff_note : '' }}</textarea>
                            </div>

                            <div class="col-md-12 px-4 payment_fields">
                                <input type="hidden" name="uploaded_file_names" id="uploaded_file_names"
                                    value="">
                                {!! Form::label('upload_documents', __('lang.upload_documents'), [
                                    'class' => 'modal-label mb-1 app()->isLocale("ar")) ? d-block text-end : "" ',
                                ]) !!}
                                <label for="upload_documents" class="upload_documents">
                                    <input type="file" class=" w-100 h-100" name="upload_documents[]"
                                        id="upload_documents" multiple>
                                </label>
                            </div>

                        </div>

                        <div class="col-md-12 mb-3 position-relative">
                            <div class="i-checks">
                                <input id="print_the_transaction" name="print_the_transaction" type="checkbox"
                                    @if (!empty($show_the_window_printing_prompt) && $show_the_window_printing_prompt == '1') checked @endif value="1"
                                    class="form-control-custom">
                                <label for="print_the_transaction"><strong>@lang('lang.print_the_transaction')</strong></label>
                            </div>
                            <span class="position-absolute modal-border"></span>
                        </div>
                        <div class="col-md-12 d-flex">
                            <button id="submit-btn" type="button"
                                class="btn btn-main mt-3">@lang('lang.submit')</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
