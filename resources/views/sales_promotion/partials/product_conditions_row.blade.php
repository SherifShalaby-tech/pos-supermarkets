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
                 <button type="button" class="btn btn-xs btn-danger text-white float-right remove_row_cp"
                       ><i class="fa fa-times"></i></button>
            </td>
        @endforeach
    </tr>
@endforeach
