<div class="row">

    <div class="col-md-12 p-1" style="height: 300px;
    overflow: scroll;">
        <div class="filter-checkbox card" style="margin: 0px;">
            {{-- @if (session('system_mode') != 'restaurant')
                <div class="card-header" style="padding: 5px 20px; color: #21912A">
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


    <div class="col-lg-12 p-1" style="height: 100%">
        <div class="card" style="height: 100%;margin-bottom:0 ">

            <div class="card-body" style="padding: 0;height: 100%">
                <div class="col-lg-12 mt-1 table-container" style="height: 100%">
                    <div class="filter-window" style="width: 100% !important; height: 100% !important">

                        <div class="category mt-3" style="height: 100%;width: 100%">
                            <div class="row ml-2 mr-2 px-2">
                                <div class="col-7">@lang('lang.choose_category')</div>
                                <div class="col-5 text-right">
                                    <span class="btn btn-default btn-sm">
                                        <i class="dripicons-cross"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap ml-2 mt-3">
                                @foreach ($categories as $category)
                                    <div class="col-lg-6 filter-by category-img text-center"
                                        data-id="{{ $category->id }}" data-type="category" style="height: 100%;">
                                        <img
                                            src="@if (!empty($category->getFirstMediaUrl('category'))) {{ $category->getFirstMediaUrl('category') }}@else{{ asset('images/default.jpg') }} @endif" />
                                        <p class="text-center">{{ $category->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="sub_category mt-3">
                            <div class="row ml-2 mr-2 px-2">
                                <div class="col-7">@lang('lang.choose_sub_category')</div>
                                <div class="col-5 text-right">
                                    <span class="btn btn-default btn-sm">
                                        <i class="dripicons-cross"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-2 mt-3">
                                @foreach ($sub_categories as $category)
                                    <div class="col-lg-3 filter-by category-img text-center"
                                        data-id="{{ $category->id }}" data-type="sub_category">
                                        <img
                                            src="@if (!empty($category->getFirstMediaUrl('category'))) {{ $category->getFirstMediaUrl('category') }}@else{{ asset('images/default.jpg') }} @endif" />
                                        <p class="text-center">{{ $category->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="brand mt-3">
                            <div class="row ml-2 mr-2 px-2">
                                <div class="col-7">@lang('lang.choose_brand')</div>
                                <div class="col-5 text-right">
                                    <span class="btn btn-default btn-sm">
                                        <i class="dripicons-cross"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap ml-2 mt-3">
                                @foreach ($brands as $brand)
                                    <div class="col-lg-3 filter-by brand-img text-center"
                                        data-id="{{ $brand->id }}" data-type="brand">
                                        <img
                                            src="@if (!empty($brand->getFirstMediaUrl('brand'))) {{ $brand->getFirstMediaUrl('brand') }}@else{{ asset('images/default.jpg') }} @endif" />
                                        <p class="text-center">{{ $brand->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                    </div>

                    <div class="table-responsive" style="max-height: 470px">
                        <table id="filter-product-table" class="table no-shadow product-list"
                            style="width: 100%; border: 0px;overflow: scroll">
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
