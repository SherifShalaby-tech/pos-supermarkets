@php
    $index = $batch_count;
@endphp
@forelse ($products as $product)
    <tr id="row_batch_details_{{ $row_count }}" class="row_batch_details row_batch_details_{{ $row_count }}"
        id="batch_number_row" style="background-color:rgb(246, 248, 248);">
        <td colspan="2"> {!! Form::label('', __('lang.new_batch'), [
            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
        ]) !!} {!! Form::text('batch_row[' . $index . '][new_batch_number]', null, [
            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start batchNumber',
            'required',
        ]) !!}
        </td>
        {{-- <td colspan="1"><img src="@if (!empty($product->getFirstMediaUrl('product'))){{$product->getFirstMediaUrl('product')}}@else{{asset('/uploads/'.session('logo'))}}@endif"
        alt="photo" width="50" height="50"></td> --}}
        {{-- <td> --}}
        {{-- @if ($product->variation_name != 'Default')
        <b>{{$product->variation_name}} {{$product->sub_sku}}</b>
        @else
        {{$product->product_name}}
        @endif --}}

        {{-- </td> --}}
        <td colspan="2">
            <input type="hidden" class="batch_product_id" name="batch_row[{{ $index }}][product_id]"
                value="{{ $product->product_id }}" />
            <input type="hidden" name="batch_row[{{ $index }}][variation_id]"
                value="{{ $product->variation_id }}" />
            {!! Form::label('', __('lang.manufacturing_date'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('batch_row[' . $index . '][batch_manufacturing_date]', null, [
                'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start datepicker',
                'readonly',
            ]) !!}
        </td>
        <td colspan="2">
            {!! Form::label('', __('lang.expiry_date'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('batch_row[' . $index . '][batch_expiry_date]', null, [
                'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start datepicker expiry_date',
                'readonly',
            ]) !!}
        </td>
        <td colspan="2">
            {!! Form::label('', __('lang.days_before_the_expiry_date'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}
            {!! Form::text('batch_row[' . $index . '][batch_expiry_warning]', null, [
                'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start days_before_the_expiry_date',
            ]) !!}
        </td>
        <td colspan="1">
            {!! Form::label('', __('lang.quantity'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
            ]) !!}

            <input type="text" data-val="0"
                class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif  batch_quantity batch_quantity{{ $product->product_id }}"
                data-id="{{ $product->product_id }}" min=1 name="batch_row[{{ $index }}][batch_quantity]"
                required value="0" index_id="">
            {{-- {!! Form::label('', __('lang.days_before_the_expiry_date'), []) !!}
        {!! Form::text('batch_row['.$i.'][expiry_warning]', null, ['class' => 'form-control days_before_the_expiry_date']) !!} --}}
        </td>
        <td colspan="1">
            <div style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-start">
                <span class="text-secondary font-weight-bold">*</span>
                <input type="text"
                    class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif  batch_purchase_price batch_purchase_price{{ $product->product_id }}"
                    data-id="{{ $product->product_id }}" name="batch_row[{{ $index }}][batch_purchase_price]"
                    required
                    value="@if ($product->purchase_price_depends == null) {{ @num_format($product->default_purchase_price / $exchange_rate) }} @else {{ @num_format($product->purchase_price_depends / $exchange_rate) }} @endif"
                    index_id="">
                <input class="final_cost" type="hidden" name="batch_row[{{ $index }}][batch_final_cost]"
                    value="@if (isset($product->default_purchase_price)) {{ @num_format($product->default_purchase_price / $exchange_rate) }}@else{{ 0 }} @endif">
            </div>
        </td>
        <td colspan="1">
            <div style="width: 100%;height: 100%;" class="d-flex justify-content-center align-items-start">

                <span class="text-secondary font-weight-bold">*</span>
                <input type="text"
                    class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif  batch_selling_price"
                    name="batch_row[{{ $index }}][batch_selling_price]" required index_id=""
                    value="@if ($product->selling_price_depends == null) {{ @num_format($product->sell_price) }} @else {{ @num_format($product->selling_price_depends) }} @endif">
            </div>
        </td>
        <td colspan="1">
            <button type="button" class="btn btn-secodary text-danger btn-sm p-1  remove_batch_row"
                data-id="{{ $product->product_id }}"><i class="fa fa-times"></i></button>
        </td>
    </tr>
@empty
@endforelse

<script>
    $('.datepicker').datepicker({
        language: "{{ session('language') }}",
        todayHighlight: true,
    })
</script>
