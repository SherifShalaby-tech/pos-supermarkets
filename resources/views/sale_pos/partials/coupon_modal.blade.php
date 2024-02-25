<!-- coupon modal -->
<div id="coupon_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title px-2 position-relative d-flex align-items-center" style="gap: 5px;">
                    {{ __('lang.coupon') }}
                    <span class="header-pill"></span>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-end  flex-column">
                    <label class="modal-label mb-1 @if (app()->isLocale('ar')) d-block text-end @endif">Type
                        Coupon Code
                        *</label>
                    <input type="text" id="coupon-code" class="form-control modal-input  @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                        placeholder="Type Coupon Code...">
                    <span class="coupon_error" style="color: red;"></span>
                </div>
                <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
                    <button type="button" class="btn btn-main coupon-check">{{ __('lang.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
