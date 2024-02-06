<div class="modal fade" tabindex="-1" role="dialog" id="weighing_scale_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div
                class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative">@lang('lang.weighing_scale')
                    <span class="position-absolute header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('weighing_scale_barcode', __('lang.weighing_scale_barcode') . ':') !!}
                            {!! Form::text('weighing_scale_barcode', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="weighing_scale_submit">@lang('lang.submit')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
