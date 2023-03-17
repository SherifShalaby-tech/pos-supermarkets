@php
    $chunkedProducts = $products->chunk(3);
@endphp

@foreach ($chunkedProducts as $chunk)
    <tr>
        @foreach ($chunk as $product)
            <td>
                @if($product->variations_name != "Default")
                    {{$product->variations_name}}
                @else
                    {{$product->name}}
                @endif
                <input type="hidden" class="product_condition_variations_ids form-control"
                       name="product_condition_variation_id[{{$product->variations_id}}]" id=""
                       value="{{$product->variations_id}}">
            </td>
        @endforeach
    </tr>
@endforeach
