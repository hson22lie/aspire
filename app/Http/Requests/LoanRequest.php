<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoanRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'status'    => 'failed',
            'message'   => $validator->errors()->first()
        ], 422));
    }

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
            'amount' => 'required',
            'term' => 'required|min:1',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'please put amount',
            'amount.min' => 'min amount is 1',
            'term.required' => 'please put required',
            'term.min' => 'please put min 1',
        ];
    }
}
