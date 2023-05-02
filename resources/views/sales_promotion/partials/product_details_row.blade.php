@foreach ($products as $product)
<tr>
    @php

        $stockLines=\App\Models\AddStockLine::where('variation_id',$product->variations_id)
        ->whereColumn('quantity',">",'quantity_sold')
        ->first();


        $default_sell_price=$stockLines?$stockLines->sell_price : $product->variations_sell_price;
        $default_purchase_price=$stockLines?$stockLines->purchase_price : $product->variations_purchase_price;
        $q=1;
        if(!empty($package_promotion_qty) &&
         array_key_exists($product->variations_id, $package_promotion_qty)){
           $q= (int)$package_promotion_qty[$product->variations_id];
        }


    @endphp
    <td><img src="@if(!empty($product->getFirstMediaUrl('product'))){{$product->getFirstMediaUrl('product')}}@else{{asset('/uploads/'.session('logo'))}}@endif"
            alt="photo" width="50" height="50"></td>
    <td>
        @if($product->variations_name != "Default")
            {{$product->variations_name}}
        @else
            {{$product->name}}
        @endif
    </td>
    <td>{{$product->variations_sku}}</td>
    <td>{{@num_format($default_purchase_price)}}</td>
    <td>{{@num_format($default_sell_price)}}</td>
    <td>@if($product->is_service){{'-'}}@else{{preg_match('/\.\d*[1-9]+/', (string)$product->current_stock) ? $product->current_stock : @num_format($product->current_stock)}}@endif</td>
    <td>@if(!empty($product->expiry_date)){{@format_date($product->expiry_date)}}@endif</td>
    <td> @if(!empty($product->date_of_purchase)){{@format_date($product->date_of_purchase)}}@endif</td>
    <td class="qty_hide @if ($type != 'package_promotion') hide @endif">
        <input type="text" class="qty product_qty_checkbox form-control"
            name="package_promotion_qty[{{$product->variations_id}}]" id=""
            value="{{$q}}">

        <input type="hidden" class="product_variations_ids form-control"
               name="product_variation_id[{{$product->variations_id}}]" id=""
               value="{{$product->variations_id}}">

    </td>
    <td><button type="button" class="btn btn-xs btn-danger text-white remove_row_sp"
            data-product_id="{{$product->variations_id}}"><i class="fa fa-times"></i></button></td>
    <input type="hidden" class="purchase_price" name="purchase_price" value="{{$default_purchase_price}}">
    <input type="hidden" class="sell_price" name="sell_price" value="{{$default_sell_price}}">
</tr>
@endforeach
<input type="hidden" name="sell_price_total" id="sell_price_total" value="{{$products->sum('sell_price')}}">
<input type="hidden" name="purchase_price_total" id="purchase_price_total" value="{{$products->sum('purchase_price')}}">
