<!-- Button trigger modal -->
<button type="button" is-show="0" id="button_product_selection_tree" class="btn btn-primary" data-toggle="modal" data-target="#pctModal" style="margin-top: 30px;">
    @lang('lang.select_products')
</button>
<style>
    .my-new-checkbox {
        margin-top: 22px;
        margin-right: 10px;
    }

    .accordion-toggle {
        color: #1391ff !important;
        width: 100%;
        border: 1px solid #d1cece;
        padding: 15px;

    }

    .accordion-toggle:hover {
        text-decoration: none;
    }

    .accordion-toggle:focus {
        text-decoration: none;
    }
</style>
<!-- Modal -->
@php
$product_class_selected = !empty($pct_data['product_class_selected']) ? $pct_data['product_class_selected'] : [];
$category_selected = !empty($pct_data['category_selected']) ? $pct_data['category_selected'] : [];
$sub_category_selected = !empty($pct_data['sub_category_selected']) ? $pct_data['sub_category_selected'] : [];
$brand_selected = !empty($pct_data['brand_selected']) ? $pct_data['brand_selected'] : [];
$product_selected = !empty($pct_data['product_selected']) ? $pct_data['product_selected'] : [];

@endphp
<div class="modal fade" id="pctModal" tabindex="-1" role="dialog" aria-labelledby="pctModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pctModalLabel">@lang('lang.products')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pct_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.next')</button>
            </div>
        </div>
    </div>
</div>
