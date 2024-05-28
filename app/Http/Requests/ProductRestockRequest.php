<?php

namespace App\Http\Requests;

use App\Helpers\ExceptionResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductRestockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "product_id" => ["required"],
            "quantity" => ["required", "numeric", "min:1"],
            "destination_address" => ["required", "max:255"],
            "supplier_name" => ["required", "max:100"],
            "supplier_address" => ["required", "max:255"],
            "supplier_phone" => ["required", "max:20"],
            "shipping_method" => ["required", "max:100"],
            "payment_method" => ["required", "max:100"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ExceptionResponseHelper::throwInvariantError($validator->getMessageBag()->first());
    }
}
