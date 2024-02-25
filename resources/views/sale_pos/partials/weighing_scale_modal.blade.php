<div class="modal fade" tabindex="-1" role="dialog" id="weighing_scale_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative d-flex align-items-center" style="gap: 5px;">@lang('lang.weighing_scale')
                    <span class=" header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="d-flex justify-content-between align-items-end  flex-column">
                        <div class="form-group">
                            {!! Form::label('weighing_scale_barcode', __('lang.weighing_scale_barcode'), [
                                'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                            ]) !!}
                            {!! Form::text('weighing_scale_barcode', null, [
                                'class' => 'form-control clear_input_form modal-input app()->isLocale("ar") ? text-end : text-start',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
                <button type="button"class="btn btn-main mb-3" id="weighing_scale_submit">@lang('lang.submit')</button>
                <button type="button" class="btn btn-danger mb-3" data-dismiss="modal">@lang('lang.close')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
