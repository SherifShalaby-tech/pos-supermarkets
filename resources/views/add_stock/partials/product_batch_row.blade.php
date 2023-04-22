@forelse ($products as $product)
@php
$i = 0;
@endphp
<tr class="batchRow">
    <td><img src="@if(!empty($product->getFirstMediaUrl('product'))){{$product->getFirstMediaUrl('product')}}@else{{asset('/uploads/'.session('logo'))}}@endif"
        alt="photo" width="50" height="50"></td>
    <td>
        @if($product->variation_name != "Default")
        <b>{{$product->variation_name}} {{$product->sub_sku}}</b>
        @else
        {{$product->product_name}}
        @endif
        <input type="hidden" name="add_stock_lines[{{$i}}][is_service]" class="is_service"
            value="{{$product->is_service}}">
        <input type="hidden" name="add_stock_lines[{{$i}}][product_id]" class="productbatch_id"
            value="{{$product->product_id}}">
        <input type="hidden" name="add_stock_lines[{{$i}}][variation_id]" class="variationbatch_id"
            value="{{$product->variation_id}}">
    </td>
    <td>
        {{$product->sub_sku}}
        
    </td>
    <td>
        <input type="text" class="form-control batchquantity quantity_{{$i}}" min=1 name="add_stock_lines[{{$i}}][quantity]" required
            value="@if(isset($product->quantity)){{@num_format($product->quantity)}}@else{{1}}@endif"  index_id="{{$i}}">
    </td>
    <td>
        <span class="text-secondary font-weight-bold">*</span>
        <input type="text" class="form-control purchase_price purchase_price_{{$i}}" name="add_stock_lines[{{$i}}][purchase_price]" required
            value="@if($product->purchase_price_depends == null) {{@num_format($product->default_purchase_price / $exchange_rate)}} @else {{@num_format($product->purchase_price_depends / $exchange_rate)}} @endif" index_id="{{$i}}">
            <input class="final_cost" type="hidden" name="add_stock_lines[{{$i}}][final_cost]" value="@if(isset($product->default_purchase_price)){{@num_format($product->default_purchase_price / $exchange_rate)}}@else{{0}}@endif"  >
    </td>
    <td>
        <span class="text-secondary font-weight-bold">*</span>
        <input type="text" class="form-control selling_price selling_price_{{$i}}" name="add_stock_lines[{{$i}}][selling_price]" required index_id="{{$i}}"
               value="@if($product->selling_price_depends == null) {{@num_format($product->sell_price)}} @else {{@num_format($product->selling_price_depends)}} @endif"  >
{{--        <input class="final_cost" type="hidden" name="add_stock_lines[{{$i}}][final_cost]" value="@if(isset($product->default_purchase_price)){{@num_format($product->default_purchase_price / $exchange_rate)}}@else{{0}}@endif">--}}
    </td>
    <td>
        <span class="sub_total_span"></span>
        <input type="hidden" class="form-control sub_total" name="add_stock_lines[{{$i}}][sub_total]" value="">
    </td>
    <td>
        <input type="hidden" name="current_stock" class="current_stock"
            value="@if($product->is_service) {{0}} @else @if(isset($product->qty_available)){{$product->qty_available}}@else{{0}}@endif @endif">
        <span
            class="current_stock_text">@if($product->is_service) {{'-'}} @else @if(isset($product->qty_available)){{@num_format($product->qty_available)}}@else{{0}}@endif @endif</span>
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