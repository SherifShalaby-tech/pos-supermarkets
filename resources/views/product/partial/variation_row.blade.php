<tr class="row_{{ $row_id }} variation_row" data-row_id="{{ $row_id }}">
    @if (!empty($item))
        {!! Form::hidden('variations[' . $row_id . '][id]', !empty($item) ? $item->id : null, [
            'class' => 'form-control',
        ]) !!}
    @endif
    @php
        $product_name = null;
        $product_sale_price = null;
        $product_purchase_price = null;
        $number_vs_base_unit = null;
        if (!empty($item)) {
            $product_name = $item->name;
            $product_sale_price = $item->sale_price;
            $product_purchase_price = $item->purchase_price;
            $number_vs_base_unit = $item->number_vs_base_unit;
        } elseif (!empty($name)) {
            $product_name = $name;
            $product_sale_price = $sell_price;
            $product_purchase_price = $purchase_price;
        }
    @endphp

    <td class="py-2" style="min-width: 120px">
        {!! Form::hidden('name_hidden', $product_name, ['class' => 'form-control name_hidden']) !!}
        {!! Form::text('variations[' . $row_id . '][name]', $product_name, [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start v_name',
        ]) !!}
    </td>
    <td class="py-2" style="min-width: 80px">{!! Form::text('variations[' . $row_id . '][sub_sku]', !empty($item) ? $item->sub_sku : null, [
        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start v_sub_sku',
    ]) !!}</td>
    <td class="py-2" style="min-width: 100px">{!! Form::select('variations[' . $row_id . '][color_id]', $colors, !empty($item) ? $item->color_id : false, [
        'class' => 'form-control selectpicker v_color',
        'data-live-search' => 'true',
        'placeholder' => '',
    ]) !!}
    </td>
    <td class="py-2" style="min-width: 100px">{!! Form::select('variations[' . $row_id . '][size_id]', $sizes, !empty($item) ? $item->size_id : false, [
        'class' => 'form-control selectpicker v_size',
        'data-live-search' => 'true',
        'placeholder' => '',
    ]) !!}
    </td>
    <td class="py-2" style="min-width: 100px">{!! Form::select('variations[' . $row_id . '][grade_id]', $grades, !empty($item) ? $item->grade_id : false, [
        'class' => 'form-control selectpicker v_grade',
        'data-live-search' => 'true',
        'placeholder' => '',
    ]) !!}
    </td>
    <td class="py-2" style="min-width: 100px">
        {!! Form::select('variations[' . $row_id . '][unit_id]', $units, !empty($item) ? $item->unit_id : false, [
            'class' => 'form-control selectpicker v_unit unit_id',
            'data-live-search' => 'true',
            'placeholder' => '',
            'onchange' => "get_unit($units_js,$row_id)",
            'id' => 'select_unit_id_' . $row_id,
        ]) !!}
    </td>
    @if (session('system_mode') != 'garments')
        <td class=" py-2" style="min-width: 100px">{!! Form::number('variations[' . $row_id . '][number_vs_base_unit]', $number_vs_base_unit, [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start
                                                                                                               number_vs_base_unit',
            'id' => 'number_vs_base_unit_' . $row_id,
        ]) !!}</td>
    @endif
    {{-- @if (empty($is_service)) hide @endif --}}
    <td style="min-width: 100px" class=" default_purchase_price_td py-2 @if (empty($is_service)) hide @endif">
        {!! Form::text('variations[' . $row_id . '][default_purchase_price]', $product_purchase_price, [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start
                                                                                                                                                                                                                                                                default_purchase_price',
        ]) !!}</td>
    <td style="min-width: 100px" class="py-2 default_sell_price_td @if (empty($is_service)) hide @endif">
        {!! Form::text('variations[' . $row_id . '][default_sell_price]', $product_sale_price, [
            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start default_sell_price',
        ]) !!}
    </td>
    <td class="py-2 d-flex justify-content-center align-items-center"> <button type="button"
            class="btn btn-danger btn-xs remove_row"><i class="dripicons-trash"></i></button>
    </td>
</tr>
<tr class="variant_store_checkbox_{{ $row_id }}">
    <td class="py-2 text-end" colspan="9">
        <input name="variant_different_prices_for_stores" type="checkbox"
            @if (!empty($item) && $item->name != 'Default') checked @endif value="1" data-row_id="{{ $row_id }}"
            class="variant_different_prices_for_stores"><strong>@lang('lang.variant_different_prices_for_stores')</strong>
    </td>
</tr>

@foreach ($stores as $store)
    <tr class="variant_store_prices_{{ $row_id }}">
        @php
            $variant_store = null;
            if (!empty($item)) {
                $variant_store = $item->product_stores->where('store_id', $store->id)->first();
            }
        @endphp
        <td colspan="5"></td>
        <td class="py-2 px-1 text-center">
            @if (!empty($variant_store))
                <input type="hidden" class="form-control"
                    name="variations[{{ $row_id }}][variant_stores][{{ $store->id }}][id]"
                    value="{{ $variant_store->id }}">
            @endif

            <input type="hidden" class="form-control"
                name="variations[{{ $row_id }}][variant_stores][{{ $store->id }}][store_id]"
                value="{{ $store->id }}">
            <input type="text"
                class="form-control modal-input @if (app()->isLocale('ar')) text-end @else  text-start @endif store_prices"
                name="variations[{{ $row_id }}][variant_stores][{{ $store->id }}][price]"
                value="@if (!empty($variant_store)) {{ $variant_store->price }} @endif">

        </td>
        <td class="py-2 px-1 text-center">
            {{ $store->name }}
        </td>

    </tr>
@endforeach
