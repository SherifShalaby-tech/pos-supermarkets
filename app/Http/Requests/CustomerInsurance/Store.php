<?php

namespace App\Http\Requests\CustomerInsurance;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'item_id' => 'required|exists:item_borroweds,id',
            'customer_id' => 'required|exists:customers,id',
            'admin_id' => 'required|exists:users,id',
            'insurance_amount' => 'required',
            'return_date' => 'required|date',
            'status' => 'required',
        ];
    }
}
