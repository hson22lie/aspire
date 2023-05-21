<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoanRepaymentRequest extends FormRequest
{
    public int $user_id;
    public int $loan_id;
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
            'amount' => 'required|min:1',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'please put installment amount',
            'amount.required' => 'please put amount minimum 1',
        ];
    }
}
