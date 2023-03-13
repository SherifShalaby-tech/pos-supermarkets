<!-- Button trigger modal -->
<button  is-show="0" id="button_product_condition_tree" type="button" class="btn btn-primary" data-toggle="modal" data-target="#pciModal" style="margin-top: 30px;">
    @lang('lang.select_products')
</button>
<style>
    .pci-my-new-checkbox {
        margin-top: 22px;
        margin-right: 10px;
    }

    .pci-accordion-toggle {
        color: #1391ff !important;
        width: 100%;
        border: 1px solid #d1cece;
        padding: 15px;

    }

    .pci-accordion-toggle:hover {
        text-decoration: none;
    }

    .pci-accordion-toggle:focus {
        text-decoration: none;
    }

</style>
<!-- Modal -->
<div class="modal fade" id="pciModal" tabindex="-1" role="dialog" aria-labelledby="pciModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pciModalLabel">@lang('lang.condition_products')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pci_modal_body">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.next')</button>
            </div>
        </div>
    </div>
</div>
