<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action('SupplierCategoryController@store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_supplier_category_form' : 'supplier_category_add_form',
        ]) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">
                @lang('lang.add_supplier_category')
                <span class="position-absolute header-modal-pill"></span>
            </h4>

            <button type="button"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div class="modal-body row justify-content-center align-items-center">
            <div class="col-6">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('name', null, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.name'),
                    'required',
                ]) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
