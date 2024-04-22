<?php

namespace App\Http\Requests;

use App\Models\TransactionStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TransactionExpenseRequest extends FormRequest
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
        $statuses = TransactionStatus::pluck("name")->toArray();

        return [
            "date" => ["required", "max:50"],
            "note" => ["nullable", "max:255"],
            "purchase_price" => ["required", "numeric", "min:0"],
            "status" => ["required", Rule::in($statuses)]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "error" => $validator->getMessageBag()->first(),
        ], 400));
    }
}
