<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImportRowRequest extends FormRequest
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
            "category" => ["required", "max:100"]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'quantity.required' => 'Product quantity is required.',
            'purchase_price.required' => 'Purchase price is required.',
            'selling_price.required' => 'Selling price is required.',
            'category.required' => 'Category is required.',
        ];
    }
}
