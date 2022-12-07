<?php

namespace App\Http\Requests\CustomerInsurance;

use Illuminate\Foundation\Http\FormRequest;

class Received extends FormRequest
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
            'id' => 'required|exists:deposit_safes,id',
            'penalties' => 'required',
            'cause_the_penalties' => 'required',
            'penalty_amount' => 'required'
        ];
    }
}
