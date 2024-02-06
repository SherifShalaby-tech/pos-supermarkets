<!-- order_tax modal -->
<div id="tax_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative">@lang('lang.tax')
                    <span class="position-absolute header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select class="form-control" name="tax_id" id="tax_id">
                        <option value="">No Tax</option>
                        @foreach ($taxes as $tax)
                            <option data-rate="{{ $tax->rate }}" @if (!empty($transaction) && $transaction->tax_id == $tax->id) selected @endif
                                value="{{ $tax->id }}">{{ $tax->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="tax_id_hidden" id="tax_id_hidden" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" name="tax_btn" id="tax_btn" class="btn btn-primary"
                        data-dismiss="modal">@lang('lang.submit')</button>
                </div>
            </div>
        </div>
    </div>
</div>
