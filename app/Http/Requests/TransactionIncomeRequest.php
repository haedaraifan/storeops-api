<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionIncomeRequest extends FormRequest
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
            "products" => ["required", "array"],
            "products.*.id" => ["required","exists:products,id"],
            "products.*.quantity" => ["required","numeric","min:1"],
            "discount" => ["nullable", "numeric", "min:0"],
            "addditional_cost" => ["nullable", "numeric", "min:0"],
            "date" => ["required", "max:50"],
            "note" => ["nullable", "max:255"],
            "status" => ["required", "exists:transaction_statuses,name"],
            "payment_method" => ["nullable", "max:20"],
            "customer_name" => ["nullable", "max:100"],
            "customer_phone" => ["nullable", "max:20"],
            "customer_address" => ["nullable", "max:255"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "error" => $validator->getMessageBag()->first(),
        ], 400));
    }
}
