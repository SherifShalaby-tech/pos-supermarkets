@if (isset($discount))
    <tr>
        <td class="px-5 py-2">
            <input type="hidden" name="discount_ids[]" value="{{ $discount->id }}">
            {!! Form::select(
                'discount_type[' . $row_id . ']',
                ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')],
                $discount->discount_type,
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'placeholder' => __('lang.please_select'),
                ],
            ) !!}
        </td>
        <td class="px-5 py-2">
            {!! Form::text('discount[' . $row_id . ']', @num_format($discount->discount), [
                'class' => 'clear_input_form form-control   modal-input app()->isLocale("ar") ? text-end : text-start ',
                'placeholder' => __('lang.discount'),
            ]) !!}
        </td>
        <td class="px-5 py-2">
            {!! Form::text('discount_category[' . $row_id . ']', $discount->discount_category, [
                'class' => 'clear_input_form form-control   modal-input app()->isLocale("ar") ? text-end : text-start ',
                'maxlength' => '4',
                'pattern' => '\d{2}',
            ]) !!}
        </td>
        {{-- <td class="py-2 px-1">

        </td> --}}
        <td class="py-2">
            {!! Form::text(
                'discount_start_date[' . $row_id . ']',
                !empty($discount->discount_start_date) ? @format_date($discount->discount_start_date) : null,
                [
                    'class' =>
                        'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start  datepicker discount_start_date',
                    'placeholder' => __('lang.discount_start_date'),
                    $discount->is_discount_permenant ? 'disabled' : '',
                ],
            ) !!}
        </td>
        <td class="py-2">
            {!! Form::text(
                'discount_end_date[' . $row_id . ']',
                !empty($discount->discount_start_date) ? @format_date($discount->discount_end_date) : null,
                [
                    'class' =>
                        'clear_input_form form-control   modal-input app()->isLocale("ar") ? text-end : text-start  datepicker discount_end_date',
                    'placeholder' => __('lang.discount_end_date'),
                    $discount->is_discount_permenant ? 'disabled' : '',
                ],
            ) !!}
        </td>
        <td class="py-2">
            {!! Form::select(
                'discount_customer_types_' . $row_id . '[]',
                $discount_customer_types,
                $discount->discount_customer_types,
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'multiple',
                    'data-actions-box' => 'true',
                    'id' => 'discount_customer_types',
                ],
            ) !!}
        </td>
        <td class="py-2">
            <button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i
                    class="fa fa-trash"></i></button>
        </td>


    </tr>

    <span class="i-checks d-flex justify-content-between align-items-center">

        <input class="is_discount_permenant" name="is_discount_permenant[{{ $row_id }}]" type="checkbox"
            @if ($discount->is_discount_permenant) checked @endif class="form-control-custom">
        <label class="mb-0" for="is_discount_permenant"><strong>
                @lang('lang.permenant')

            </strong></label>
    </span>
@elseif(isset($discount_product))
    <tr>
        <td class="py-2">

            {!! Form::select(
                'discount_type[' . $row_id . ']',
                ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')],
                !empty($discount_product) ? $discount_product->discount_type : 'fixed',
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'placeholder' => __('lang.please_select'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::text('discount[' . $row_id . ']', @num_format($discount_product->discount), [
                'class' => 'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.discount'),
            ]) !!}
        </td>
        <td class="py-2">

            {!! Form::text('discount_category[' . $row_id . ']', $discount_product->discount_category, [
                'class' => 'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'maxlength' => '4',
                'pattern' => '\d{2}',
            ]) !!}
        </td>
        {{-- <td class="py-2 px-1">


        </td> --}}
        <td class="py-2">

            {!! Form::text(
                'discount_start_date[' . $row_id . ']',
                !empty($discount_product->discount_start_date) ? @format_date($discount_product->discount_start_date) : null,
                [
                    'class' =>
                        'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker',
                    'placeholder' => __('lang.discount_start_date'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::text(
                'discount_end_date[' . $row_id . ']',
                !empty($discount_product->discount_end_date) ? @format_date($discount_product->discount_end_date) : null,
                [
                    'class' =>
                        'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker',
                    'placeholder' => __('lang.discount_end_date'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::select(
                'discount_customer_types_' . $row_id . '[]',
                $discount_customer_types,
                !empty($discount_product) ? $discount_product->discount_customer_types : false,
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'multiple',
                    'data-actions-box' => 'true',
                    'id' => 'discount_customer_types',
                ],
            ) !!}
        </td>
        <td class="py-2">
            <button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i
                    class="fa fa-trash"></i></button>
        </td>

    </tr>
    second
    <span class="i-checks d-flex justify-content-between align-items-center">
        second
        <input class="is_discount_permenant" name="is_discount_permenant[{{ $row_id }}]" type="checkbox" checked
            class="form-control-custom">
        <label class="mb-0" for="is_discount_permenant"><strong>
                @lang('lang.permenant')

            </strong></label>
    </span>
@else
    <tr>
        <td class="py-2">

            {!! Form::select(
                'discount_type[' . $row_id . ']',
                ['fixed' => __('lang.fixed'), 'percentage' => __('lang.percentage')],
                !empty($recent_product) ? $recent_product->discount_type : 'fixed',
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'placeholder' => __('lang.please_select'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::text(
                'discount[' . $row_id . ']',
                !empty($recent_product) ? @num_format($recent_product->discount) : null,
                [
                    'class' => 'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.discount'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::text(
                'discount_category[' . $row_id . ']',
                !empty($recent_product) ? $recent_product->discount_category : null,
                [
                    'class' => 'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'maxlength' => '4',
                    'pattern' => '\d{2}',
                ],
            ) !!}
        </td>
        {{-- <td class="py-2 px-1">


        </td> --}}
        <td class="py-2">

            {!! Form::text(
                'discount_start_date[' . $row_id . ']',
                !empty($recent_product) && !empty($recent_product->discount_start_date)
                    ? @format_date($recent_product->discount_start_date)
                    : null,
                [
                    'class' =>
                        'clear_input_form  form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker discount_start_date',
                    'disabled',
                    'placeholder' => __('lang.discount_start_date'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::text(
                'discount_end_date[' . $row_id . ']',
                !empty($recent_product) && !empty($recent_product->discount_end_date)
                    ? @format_date($recent_product->discount_end_date)
                    : null,
                [
                    'class' =>
                        'clear_input_form form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker discount_end_date',
                    'disabled',
                    'placeholder' => __('lang.discount_end_date'),
                ],
            ) !!}
        </td>
        <td class="py-2">

            {!! Form::select(
                'discount_customer_types_' . $row_id . '[]',
                $discount_customer_types,
                !empty($recent_product) ? $recent_product->discount_customer_types : false,
                [
                    'class' => 'clear_input_form selectpicker form-control',
                    'data-live-search' => 'true',
                    'style' => 'width: 80%',
                    'multiple',
                    'data-actions-box' => 'true',
                    'id' => 'discount_customer_types',
                ],
            ) !!}
        </td>
        <td class="py-2">
            <button type="button" class="btn btn-xs btn-danger remove_row remove_discount_btn"><i
                    class="fa fa-trash"></i></button>
        </td>

    </tr>
    third
    <span class="i-checks d-flex justify-content-between align-items-center">
        third
        <input class="is_discount_permenant" name="is_discount_permenant[{{ $row_id }}]" type="checkbox" checked
            class="form-control-custom">
        <label class="mb-0" for="is_discount_permenant"><strong>
                @lang('lang.permenant')

            </strong></label>
    </span>
@endif
