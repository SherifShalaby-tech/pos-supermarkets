
@if(isset($discount))
    <tr>
        <td>
            <input type="hidden" name="discount_ids[]" value="{{$discount->id}}">
            {!! Form::select('discount_type['.$row_id.']', ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')],  $discount->discount_type, ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
        </td>
        <td>
            {!! Form::text('discount['.$row_id.']',  @num_format($discount->discount), ['class' => 'clear_input_form form-control', 'placeholder' => __('lang.discount')]) !!}
        </td>
        <td>
            {!! Form::text('discount_start_date['.$row_id.']', @format_date($discount->discount_start_date) , ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_start_date')]) !!}
        </td>
        <td>
            {!! Form::text('discount_end_date['.$row_id.']',  @format_date($discount->discount_end_date) , ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_end_date')]) !!}
        </td>
        <td>
            {!! Form::select('discount_customer_types_'.$row_id.'[]', $discount_customer_types,  $discount->discount_customer_types , ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'multiple', 'data-actions-box' => 'true', 'id' => 'discount_customer_types']) !!}
        </td>
        <td><button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i class="fa fa-times"></i></button></td>

    </tr>
@elseif(isset($discount_product))
<tr>
    <td>
        {!! Form::select('discount_type['.$row_id.']', ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')], !empty($discount_product) ? $discount_product->discount_type : 'fixed', ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
    </td>
    <td>
        {!! Form::text('discount['.$row_id.']', @num_format($discount_product->discount) , ['class' => 'clear_input_form form-control', 'placeholder' => __('lang.discount')]) !!}
    </td>
    <td>
        {!! Form::text('discount_start_date['.$row_id.']',  !empty($discount_product->discount_start_date) ? @format_date($discount_product->discount_start_date) : null, ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_start_date')]) !!}
    </td>
    <td>
        {!! Form::text('discount_end_date['.$row_id.']', !empty($discount_product->discount_end_date) ? @format_date($discount_product->discount_end_date) : null , ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_end_date')]) !!}
    </td>
    <td>
        {!! Form::select('discount_customer_types_'.$row_id.'[]', $discount_customer_types, !empty($discount_product) ? $discount_product->discount_customer_types : false, ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'multiple', 'data-actions-box' => 'true', 'id' => 'discount_customer_types']) !!}
    </td>
    <td><button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i class="fa fa-times"></i></button></td>

</tr>
@else
    <tr>
        <td>
            {!! Form::select('discount_type['.$row_id.']', ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')], !empty($recent_product) ? $recent_product->discount_type : 'fixed', ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select')]) !!}
        </td>
        <td>
            {!! Form::text('discount['.$row_id.']', !empty($recent_product) ? @num_format($recent_product->discount) : null, ['class' => 'clear_input_form form-control', 'placeholder' => __('lang.discount')]) !!}
        </td>
        <td>
            {!! Form::text('discount_start_date['.$row_id.']', !empty($recent_product) && !empty($recent_product->discount_start_date) ? @format_date($recent_product->discount_start_date) : null, ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_start_date')]) !!}
        </td>
        <td>
            {!! Form::text('discount_end_date['.$row_id.']', !empty($recent_product) && !empty($recent_product->discount_end_date) ? @format_date($recent_product->discount_end_date) : null, ['class' => 'clear_input_form form-control datepicker', 'placeholder' => __('lang.discount_end_date')]) !!}
        </td>
        <td>
            {!! Form::select('discount_customer_types_'.$row_id.'[]', $discount_customer_types, !empty($recent_product) ? $recent_product->discount_customer_types : false, ['class' => 'clear_input_form selectpicker form-control', 'data-live-search' => 'true', 'style' => 'width: 80%', 'multiple', 'data-actions-box' => 'true', 'id' => 'discount_customer_types']) !!}
        </td>
        <td><button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i class="fa fa-times"></i></button></td>

    </tr>
@endif
