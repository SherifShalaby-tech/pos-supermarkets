@forelse ($products as $product)
    <tr class="product_row">
        @if (!empty($is_direct_sale))
            <td class="row_number"></td>
        @endif
        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 17%; @else 20%; @endif height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">
                @php
                    $Variation = \App\Models\Variation::where('id', $product->variation_id)->first();
                    if ($Variation) {
                        $stockLines = \App\Models\AddStockLine::where('sell_price', '>', 0)
                            ->where('variation_id', $Variation->id)
                            ->latest()
                            ->first();
                        $default_sell_price = $stockLines ? $stockLines->sell_price : $Variation->default_sell_price;
                        $default_purchase_price = $stockLines ? $stockLines->purchase_price : $Variation->default_purchase_price;
                        $cost_ratio_per_one = $stockLines ? $stockLines->cost_ratio_per_one : 0;
                    }
                    $product_unit = \App\Models\Product::where('id', $product->product_id)->first();
                    if ($product_unit && isset($product_unit->multiple_units)) {
                        foreach ($product_unit->multiple_units as $unit) {
                            $check_unit = \App\Models\Unit::where('id', $unit)->first();
                        }
                    }
                    
                @endphp
                @if ($product->variation_name != 'Default')
                    <b style="display: block">{{ $product->variation_name }}</b> <b>SKU:</b>{{ $product->sub_sku }}
                @else
                    <b>{{ $product->product_name }}</b>
                    <p class="m-0">
                        @php
                            $ex = 'id' . $product->variation_id;
                        @endphp

                        <input type="hidden" id="{{ $ex }}" name="old_ex" value="1">
                    </p>
                @endif

                {{-- <small class="text-danger">
                    @if ($Variation->unit)
                        <br>{{ $Variation->unit->name }}
                    @endif
                </small> --}}

                {{-- <small>
                    @if ($product->batch_number)
                        <br>{{ $product->batch_number }}
                    @endif
                </small> --}}

                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][is_service]"
                    class="is_service" value="{{ $product->is_service }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][have_weight]"
                    class="have_weight" value="{{ $product->have_weight }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][product_id]"
                    class="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][variation_id]"
                    class="variation_id" value="{{ $product->variation_id }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][stock_id]"
                    class="batch_number_id"
                    value="@if ($product->stock_id) {{ $product->stock_id }}@else {{ false }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][batch_number]"
                    class="batch_number"
                    value="@if ($product->batch_number) {{ $product->batch_number }}@else {{ false }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][price_hidden]"
                    class="price_hidden"
                    value="@if (isset($default_sell_price)) {{ @num_format($default_sell_price / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][purchase_price]"
                    class="purchase_price"
                    value="@if (isset($default_purchase_price)) {{ @num_format($default_purchase_price / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][cost_ratio_per_one]"
                    class="cost_ratio_per_one"
                    value="@if (isset($cost_ratio_per_one)) {{ @num_format($cost_ratio_per_one / $exchange_rate) }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][tax_id]" class="tax_id"
                    value="{{ $product->tax_id }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][tax_method]"
                    class="tax_method" value="{{ $product->tax_method }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][tax_rate]"
                    class="tax_rate" value="{{ @num_format($product->tax_rate) }}">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][item_tax]"
                    class="item_tax" value="0">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][coupon_discount]"
                    class="coupon_discount_value" value="0">
                <!-- value is percentage or fixed value from coupon data -->
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][coupon_discount_type]"
                    class="coupon_discount_type" value="">
                <!-- value is percentage or fixed value from coupon data -->
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][coupon_discount_amount]"
                    class="coupon_discount_amount" value="0">
                <!-- after calculation actual discounted amount for row product row -->
                <input type="hidden"
                    name="transaction_sell_line[{{ $loop->index + $index }}][promotion_purchase_condition]"
                    class="promotion_purchase_condition"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->purchase_condition }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{ $loop->index + $index }}][promotion_purchase_condition_amount]"
                    class="promotion_purchase_condition_amount"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->purchase_condition_amount }}@else{{ 0 }} @endif">
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][promotion_discount]"
                    class="promotion_discount_value"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->discount_value }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{ $loop->index + $index }}][promotion_discount_type]"
                    class="promotion_discount_type"
                    value="@if (!empty($sale_promotion_details)) {{ $sale_promotion_details->discount_type }}@else{{ 0 }} @endif">
                <input type="hidden"
                    name="transaction_sell_line[{{ $loop->index + $index }}][promotion_discount_amount]"
                    class="promotion_discount_amount" value="0">
                @php $loop_index= $loop->index + $index @endphp
            </div>
        </td>



        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 12%; @else 20%; @endif height:40px">
            <div
                style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%; padding-top: 4px;padding-bottom: 4px;">
                <div class="input-group justify-content-between align-items-center flex-column flex-lg-row"
                    style="height: 100%">
                    <span class="input-group-btn">
                        <button type="button" class="btn p-0 p-lg-1 btn-danger minus">
                            <span class="dripicons-minus"></span>
                        </button>
                    </span>

                    @php
                        
                        $inputValue = !empty($edit_quantity) ? $edit_quantity : (isset($product->quantity) ? (preg_match('/\.\d*[1-9]+/', (string) $product->quantity) ? $product->quantity : number_format($product->quantity)) : 1);
                        
                        // Create a regular expression to match the whitespace between the `value` attribute and the value.
                        $regex = '/\s+/';
                        
                        // Replace the whitespace with an empty string.
                        $convertedInputValue = preg_replace($regex, '', $inputValue);
                    @endphp


                    <input type="number" class="form-control quantity  qty numkey input-number" step="any"
                        autocomplete="off" style="width: 50px;"
                        @isset($check_unit) @if ($check_unit->name == 'قطعه' || $check_unit->name == 'Piece') oninput="this.value = Math.round(this.value);" @endif @endisset
                        id="quantity" @if (!$product->is_service) max="{{ $product->qty_available }}" @endif
                        name="transaction_sell_line[{{ $loop->index + $index }}][quantity]" required
                        value="{{ $convertedInputValue }}">
                    {{--
                    <input type="number" class="form-control quantity  qty numkey input-number" step="any"
                        autocomplete="off" style="width: 50px;"
                        @isset($check_unit) @if ($check_unit->name == 'قطعه' || $check_unit->name == 'Piece') oninput="this.value = Math.round(this.value);" @endif @endisset
                        id="quantity" @if (!$product->is_service) max="{{ $product->qty_available }}" @endif
                        name="transaction_sell_line[{{ $loop->index + $index }}][quantity]" required
                        value="@if (!empty($edit_quantity)) {{ $edit_quantity }}@else @if (isset($product->quantity)){{ preg_match('/\.\d*[1-9]+/', (string) $product->quantity) ? $product->quantity : @num_format($product->quantity) }}@else{{ 1 }} @endif @endif"> --}}


                    <span class="input-group-btn">
                        <button type="button"
                            style="background-color:#21912A;color: white;font-weight: 700;font-size: 14px "
                            class="btn plus p-0 p-lg-1">
                            <span class="dripicons-plus"></span>
                        </button>
                    </span>
                </div>
            </div>
        </td>



        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 12%; @else 15%; @endif height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                <input type="text" class="form-control sell_price text-center"
                    style="outline: none;border: none;font-size: 11px;padding: 0 !important;width: 100%;height: 100%;"
                    class="d-flex justify-content-center align-items-center"
                    name="transaction_sell_line[{{ $loop->index + $index }}][sell_price]" required
                    @if (!auth()->user()->can('product_module.sell_price.create_and_edit')) readonly @elseif(env('IS_SUB_BRANCH', false)) readonly @endif
                    value="@if (isset($default_sell_price)) {{ @num_format($default_sell_price / $exchange_rate) }}@else{{ 0 }} @endif ">
            </div>
        </td>


        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 11%; @else 15%; @endif height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                @php
                    $discountType = !empty($product_discount_details->discount_type) ? $product_discount_details->discount_type : 0;
                    
                    $discountAmount = !empty($product_discount_details->discount) ? number_format($product_discount_details->discount) : 0;
                    
                    // Create a regular expression to match the whitespace between the `value` attribute and the value.
                    $regex = '/\s+/';
                    
                    // Replace the whitespace with an empty string.
                    $convertedDiscountType = preg_replace($regex, '', $discountType);
                    
                    $convertedDiscountAmount = preg_replace($regex, '', $discountAmount);
                @endphp

                <div class="input-group" style="width: 100%;height: 100%;">
                    <input type="hidden"
                        class="form-control product_discount_type  discount_type{{ $product->product_id }}"
                        name="transaction_sell_line[{{ $loop->index + $index }}][product_discount_type]"
                        value="{{ $convertedDiscountType }}">


                    <input type="hidden"
                        class="form-control product_discount_value  discount_value{{ $product->product_id }}"
                        name="transaction_sell_line[{{ $loop->index + $index }}][product_discount_value]"
                        value="{{ $convertedDiscountAmount }}">


                    <div class="d-flex justify-content-center align-items-center">

                        <button type="button" style="border: none;outline: none" id="search_button"><span
                                class="plus_sign_text">+</span></button>

                        <input type="text" style="outline: none;border: none;font-size: 13px;padding: 0!important;"
                            class="form-control product_discount_amount  discount_amount{{ $product->product_id }}"
                            name="transaction_sell_line[{{ $loop->index + $index }}][product_discount_amount]"
                            readonly value="{{ $convertedDiscountAmount }}">

                    </div>
                </div>
            </div>
            </div>
        </td>


        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 11% @else 15% @endif; height:40px; ">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                <input type="hidden" value="{{ $product->product_id }}" class="p-id" />
                @if (auth()->user()->can('sp_module.sales_promotion.view') ||
                        auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
                        auth()->user()->can('sp_module.sales_promotion.delete'))
                    <select
                        class="custom-select custom-select-sm discount_category discount_category{{ $product->product_id }}"
                        style="height:100%">
                        <option selected>select</option>
                        @if (!empty($product_all_discounts_categories))
                            @foreach ($product_all_discounts_categories as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->discount_category }}</option>
                            @endforeach
                        @endif
                    </select>
                @else
                    <select
                        class="custom-select custom-select-sm discount_category discount_category{{ $product->product_id }}"
                        style="height:100% !important" disabled="disabled">
                        <option selected>select</option>
                        @if (!empty($product_all_discounts_categories))
                            @foreach ($product_all_discounts_categories as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->discount_category }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif
                <input type="hidden" name="transaction_sell_line[{{ $loop->index + $index }}][discount_category]"
                    class="discount_category_name{{ $product->product_id }}" />
            </div>
        </td>





        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 9% @else 15% @endif; height:40px">
            <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">


                <span class="sub_total_span d-flex justify-content-center align-items-center"
                    style="font-weight: bold;width: 100%; height: 100%;"></span>
                <input type="hidden" class="form-control sub_total"
                    name="transaction_sell_line[{{ $loop->index + $index }}][sub_total]">
            </div>
        </td>


        @if (session('system_mode') != 'restaurant')
            <td
                style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 9% @else 15% @endif;height:40px">
                <div style=" border:2px solid #dcdcdc;border-radius:5px;width: 100%;height: 100%;">

                    <div class="d-flex justify-content-center align-items-center" style="width: 100%;height: 100%;">

                        @if ($product->is_service)
                            {{ '-' }}
                        @else
                            @if (isset($product->qty_available))
                                {{ preg_match('/\.\d*[1-9]+/', (string) $product->qty_available) ? $product->qty_available : @num_format($product->qty_available) }}@else{{ 0 }}
                            @endif
                        @endif
                    </div>
                </div>
            </td>
        @endif

        <td
            style="font-size: 12px;padding:3px;margin:2px;width: @if (session('system_mode') != 'restaurant') 9%; @else 15%; @endif padding: 0px;height:40px;border:none;">
            @if (!empty($dining_table_id))
                @if (auth()->user()->can('superadmin') || auth()->user()->is_admin == 1)
                    <button type="button" class="btn btn-danger btn-xs remove_row" style="margin-top: 15px;"><i
                            class="fa fa-times"></i></button>
                @endif
            @else
                <div class="d-flex justify-content-center align-items-center" style="width: 100%;height: 100%;">

                    <button type="button" class="btn btn-xs remove_row"
                        style="background-color: transparent;outline: none;border: none">
                        <div class="image-responsive">
                            <img style="width: 100%; border-radius: 5px" src="{{ url('images/delete.png') }}"
                                alt="">
                        </div>
                    </button>
            @endif
            @if (session('system_mode') != 'restaurant')
                <button type="button" class="btn btn-xs quick_add_purchase_order"
                    style="background-color: transparent;outline: none;border: none" title="@lang('lang.add_draft_purchase_order')"
                    data-href="{{ action('PurchaseOrderController@quickAddDraft') }}?variation_id={{ $product->variation_id }}&product_id={{ $product->product_id }}">
                    <div class="image-responsive">
                        <img style="width: 100%; border-radius: 5px;height: 100%;" src="{{ url('images/add.png') }}"
                            alt="">
                    </div>
                </button>
            @endif
            </div>
        </td>
    </tr>
@empty
@endforelse
