<?php

namespace App\Http\Requests;

use App\Helpers\ExceptionResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            "name"=> ["required", "max:100"],
            "quantity" => ["required", "numeric", "min:0"],
            "purchase_price" => ["required", "numeric", "min:0"],
            "selling_price" => ["required", "numeric", "min:0"],
            "unit" => ["nullable", "exists:product_units,name"],
            "category" => ["required", "max:100"],
            "image" => ["nullable", "mimes:jpg,jpeg,png", "max:2048"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ExceptionResponseHelper::throwInvariantError($validator->getMessageBag()->first());
    }
}
