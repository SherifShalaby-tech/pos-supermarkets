<!-- order_sale_note modal -->
<div id="sale_note_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative">@lang('lang.sale_note')
                    <span class="position-absolute header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-end  flex-column">
                    {!! Form::label('sale_note', __('lang.sale_note'), [
                        'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                    ]) !!}
                    <textarea rows="3" class="form-control modal-input" name="sale_note_draft" id="sale_note_draft">{{ !empty($transaction) ? $transaction->sale_note : '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button data-method="draft" type="button" class="btn btn-main mt-3 " id="draft-btn"><i
                        class="dripicons-flag"></i>
                    @lang('lang.save')</button>
            </div>
        </div>
    </div>
</div>
</div>
