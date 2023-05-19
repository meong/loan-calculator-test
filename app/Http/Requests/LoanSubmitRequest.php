<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "amount" => "required|numeric|gt:0",
            "rate" => "required|numeric|gt:0|lte:100",
            "term" => "required|numeric|gt:0",
            "extra_payment" => "nullable|numeric|gt:0",
        ];
    }
}
