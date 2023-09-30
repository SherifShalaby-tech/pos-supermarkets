<style>
    .filterLabel {

        display: grid;
        grid-template-columns: 1em auto;
        gap: 0.5em;
    }

    .filterInput {
        appearance: none;
        background-color: transparent;
        margin: 0;
        font: inherit;
        color: #21912A;
        width: 1.15em;
        height: 1.15em;
        border: 0.15em solid #21912A;
        border-radius: 0.15em;
        transform: translateY(-0.075em);
        display: grid;
        place-content: center;
        margin-top: 2px;
    }

    /*  */

    .filterInput::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em var(--form-control-color);

        background-color: CanvasText;

        transform-origin: bottom left;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    .filterInput:checked::before {
        transform: scale(1);
        background-color: white;
    }

    .filterInput:checked {
        background-color: #21912A
    }
</style>

<div class="row">
    <br>
    <div class="col-md-12">
        <div class="filter-checkbox card" style="margin: 0px;">
            {{-- @if (session('system_mode') != 'restaurant')
                <div class="card-header" style="padding: 5px 20px; color: #7c5cc4">
                    <i class="fa fa-filter"></i> @lang('lang.filter')
                </div>
            @endif --}}
            <div class="card-body" style="padding: 5px 20px">


                <div class="row p-2">
                    @if (session('system_mode') != 'restaurant')
                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">

                            <label class="filterLabel">
                                <input class="filterInput" type="checkbox" id="category-filter" />
                                @lang('lang.category')
                            </label>




                            <label class="filterLabel">
                                <input class="filterInput" type="checkbox" id="sub-category-filter" />
                                @lang('lang.sub_category')
                            </label>



                            <label class="filterLabel">
                                <input class="filterInput" type="checkbox" id="brand-filter" />
                                @lang('lang.brand')
                            </label>
                        </div>

                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">

                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="selling_filter filterInput" value="best_selling">
                                @lang('lang.best_selling')
                            </label>
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="selling_filter filterInput" value="slow_moving_items">
                                @lang('lang.slow_moving_items')
                            </label>
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="selling_filter filterInput"
                                    value="product_in_last_transactions">
                                @lang('lang.product_in_last_transactions')
                            </label>
                        </div>

                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="price_filter filterInput" value="highest_price">
                                @lang('lang.highest_price')
                            </label>
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="price_filter filterInput" value="lowest_price">
                                @lang('lang.lowest_price')
                            </label>
                        </div>


                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="sorting_filter filterInput" value="a_to_z">
                                @lang('lang.a_to_z')
                            </label>
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="sorting_filter filterInput" value="z_to_a">
                                @lang('lang.z_to_a')
                            </label>
                        </div>
                        {{--  --}}

                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="expiry_filter filterInput" value="nearest_expiry">
                                @lang('lang.nearest_expiry')
                            </label>
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="expiry_filter filterInput" value="longest_expiry">
                                @lang('lang.longest_expiry')
                            </label>
                        </div>
                    @endif



                    <div class=" @if (session('system_mode') == 'restaurant') hide @endif">
                        <div style="background-color: #E6E6E6;color: black;border-radius: 16px;width: 100%;box-shadow: 5px 8px 4px -5px #bbb inset;"
                            class="d-flex mb-3 flex-column  px-2 pt-3">
                            <label class="checkbox-inline filterLabel">
                                <input type="checkbox" class="sale_promo_filter filterInput"
                                    value="items_in_sale_promotion">
                                @lang('lang.items_in_sale_promotion')
                            </label>
                        </div>
                    </div>

                    @if (session('system_mode') == 'restaurant')
                        <div style="width: 100%" class=" filter-btn-div">
                            {{-- class="btn-group-custom" --}}
                            <div class="btn-group btn-group-toggle  " data-toggle="buttons">
                                <label class="btn btn-primary active filter-btn">
                                    <input type="radio" checked autocomplete="off" name="restaurant_filter"
                                        value="all">
                                    @lang('lang.all')
                                </label>
                                <label class="btn btn-primary filter-btn">
                                    <input type="radio" autocomplete="off" name="restaurant_filter"
                                        value="promotions">
                                    @lang('lang.promotions')
                                </label>
                                @foreach ($product_classes as $product_class)
                                    <label class="btn btn-primary filter-btn">
                                        <input type="radio" name="restaurant_filter" value="{{ $product_class->id }}"
                                            autocomplete="off"
                                            id="{{ $product_class->name . '_' . $product_class->id }}">
                                        {{ ucfirst($product_class->name) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
