@php 
$index=$batch_count;
@endphp
@forelse ($products as $product)

<tr class="row_batch_details row_batch_details_{{$row_count}}" id="batch_number_row" style="background-color:rgb(246, 248, 248);">
    <td> {!! Form::label('', __('lang.new_batch'), []) !!} <br> {!!
        Form::text('batch_row['.$index.'][new_batch_number]', null, ['class' => 'form-control batchNumber','required']) !!}
    </td>
    {{-- <td colspan="1"><img src="@if(!empty($product->getFirstMediaUrl('product'))){{$product->getFirstMediaUrl('product')}}@else{{asset('/uploads/'.session('logo'))}}@endif"
        alt="photo" width="50" height="50"></td> --}}
    {{-- <td> --}}
        {{-- @if($product->variation_name != "Default")
        <b>{{$product->variation_name}} {{$product->sub_sku}}</b>
        @else
        {{$product->product_name}}
        @endif --}}
        
    {{-- </td> --}}
    <td colspan="1">
        <input type="hidden"  class="batch_product_id" name="batch_row[{{$index}}][product_id]" value="{{$product->product_id}}"/>
        <input type="hidden" name="batch_row[{{$index}}][variation_id]" value="{{$product->variation_id}}"/>
        {!! Form::label('', __('lang.manufacturing_date'), []) !!}<br><br>
        {!! Form::text('batch_row['.$index.'][batch_manufacturing_date]', null, ['class' => 'form-control datepicker',
        'readonly']) !!}
    </td>
    <td colspan="1"> 
        {!! Form::label('', __('lang.expiry_date'), []) !!}<br>
        {!! Form::text('batch_row['.$index.'][batch_expiry_date]', null, ['class' => 'form-control datepicker expiry_date',
        'readonly']) !!}
    </td>
    <td colspan="1">
        {!! Form::label('', __('lang.days_before_the_expiry_date'), []) !!}<br>
        {!! Form::text('batch_row['.$index.'][batch_expiry_warning]', null, ['class' => 'form-control days_before_the_expiry_date']) !!}
    </td>
    <td colspan="1">
        {!! Form::label('', __('lang.quantity'), []) !!}<br>
        
        <input type="text" data-val="0"  class="form-control batch_quantity batch_quantity{{$product->product_id}}" data-id="{{$product->product_id}}" min=1 name="batch_row[{{$index}}][batch_quantity]" required
            value="0"  index_id="">
         {{-- {!! Form::label('', __('lang.days_before_the_expiry_date'), []) !!}<br>
        {!! Form::text('batch_row['.$i.'][expiry_warning]', null, ['class' => 'form-control days_before_the_expiry_date']) !!} --}}
    </td>
    <td colspan="1"></td>
    <td colspan="1">
        <span class="text-secondary font-weight-bold">*</span>
        <input type="text" class="form-control batch_purchase_price batch_purchase_price{{$product->product_id}}" data-id="{{$product->product_id}}" name="batch_row[{{$index}}][batch_purchase_price]" required
            value="@if($product->purchase_price_depends == null) {{@num_format($product->default_purchase_price / $exchange_rate)}} @else {{@num_format($product->purchase_price_depends / $exchange_rate)}} @endif" index_id="">
            <input class="final_cost" type="hidden" name="batch_row[{{$index}}][batch_final_cost]" value="@if(isset($product->default_purchase_price)){{@num_format($product->default_purchase_price / $exchange_rate)}}@else{{0}}@endif"  >
    </td>
    <td colspan="1">
        <span class="text-secondary font-weight-bold">*</span>
        <input type="text" class="form-control batch_selling_price" name="batch_row[{{$index}}][batch_selling_price]" required index_id=""
               value="@if($product->selling_price_depends == null) {{@num_format($product->sell_price)}} @else {{@num_format($product->selling_price_depends)}} @endif"  >
    </td>
    <td colspan="2"></td>
    <td colspan="1">
        <button style="margin-top: 33px;" type="button" class="btn btn-secodary text-danger btn-sx remove_batch_row" data-id="{{$product->product_id}}"><i
            class="fa fa-times"></i></button>
    </td>
</tr>
@empty

@endforelse

<script>
    $('.datepicker').datepicker({
        language: "{{session('language')}}",
        todayHighlight: true,
    })
</script>