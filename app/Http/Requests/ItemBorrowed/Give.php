<?php

namespace App\Http\Requests\ItemBorrowed;

use Illuminate\Foundation\Http\FormRequest;

class Give extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:item_borroweds,id',
            'deposit_amount' => 'required',
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date'
        ];
    }
}
