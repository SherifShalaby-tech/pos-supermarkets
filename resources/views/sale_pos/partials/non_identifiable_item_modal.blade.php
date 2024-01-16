<div class="modal fade" tabindex="-1" role="dialog" id="non_identifiable_item_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title px-2 position-relative">@lang('lang.non_identifiable_item')
                    <span class="position-absolute header-pill" style="right: -20%"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-end  flex-column">
                            {!! Form::label('nonid_name', __('lang.name'), [
                                'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                            ]) !!}
                            {!! Form::text('nonid_name', null, ['class' => 'form-control modal-input', 'id' => 'nonid_name']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-end  flex-column">
                            {!! Form::label('nonid_purchase_price', __('lang.purchase_price'), [
                                'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                            ]) !!}
                            {!! Form::text('nonid_purchase_price', null, [
                                'class' => 'form-control modal-input',
                                'id' => 'nonid_purchase_price',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-end  flex-column">
                            {!! Form::label('nonid_sell_price', __('lang.sell_price'), [
                                'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                            ]) !!}
                            {!! Form::text('nonid_sell_price', null, [
                                'class' => 'form-control modal-input',
                                'id' => 'nonid_sell_price',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-end  flex-column">
                            {!! Form::label('nonid_quantity', __('lang.quantity'), [
                                'class' => 'modal-label mb-1 app()->isLocale("ar") ? d-block text-end : d-block',
                            ]) !!}
                            {!! Form::text('nonid_quantity', null, [
                                'class' => 'form-control modal-input',
                                'id' => 'nonid_quantity',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-main mt-3 "
                    id="non_identifiable_submit">@lang('lang.submit')</button>
                <button type="button" class="btn btn-danger mt-3 " data-dismiss="modal">@lang('lang.close')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
