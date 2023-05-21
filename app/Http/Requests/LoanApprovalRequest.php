<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoanApprovalRequest extends FormRequest
{
    public int $admin_id;
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
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'please put status approved / rejected',
        ];
    }
}
