<!-- order_discount modal -->
<div id="discount_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 id="exampleModalLabel" class="modal-title position-relative d-flex align-items-center" style="gap: 5px;">@lang('lang.random_discount')
                    <span class=" header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body ">
                <div class="d-flex">
                    <div class="form-group col-md-6 d-flex justify-content-between align-items-end  flex-column">
                        {!! Form::label('discount_type', __('lang.type') . '*', [
                            'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                        ]) !!}
                        {!! Form::select(
                            'discount_type',
                            ['fixed' => 'Fixed', 'percentage' => 'Percentage'],
                            !empty($transaction) ? $transaction->discount_type : 'fixed',
                            ['class' => 'form-control  modal-input', 'data-live-search' => 'true'],
                        ) !!}
                    </div>
                    <div class="form-group col-md-6 d-flex justify-content-between align-items-end  flex-column">
                        {!! Form::label('discount_value', __('lang.discount_value') . '*', [
                            'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                        ]) !!}
                        {!! Form::text('discount_value', !empty($transaction) ? @num_format($transaction->discount_value) : null, [
                            'class' => 'form-control  modal-input',
                            'placeholder' => __('lang.discount_value'),
                            'required',
                        ]) !!}
                    </div>
                </div>

                <input type="hidden" name="discount_amount" id="discount_amount">
                <div class="modal-footer  d-flex justify-content-center">
                    <button type="button" name="discount_btn" id="discount_btn" class="btn btn-main mt-3 "
                        data-dismiss="modal">@lang('lang.submit')</button>
                    <button type="button" class="btn btn-danger mt-3 " data-dismiss="modal">@lang('lang.close')</button>

                </div>
            </div>
        </div>
    </div>
</div>
