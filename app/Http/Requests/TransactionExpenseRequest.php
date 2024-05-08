<?php

namespace App\Http\Requests;

use App\Helpers\ExceptionResponseHelper;
use App\Models\TransactionStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            "date" => ["required", "max:50"],
            "note" => ["nullable", "max:255"],
            "purchase_price" => ["required", "numeric", "min:0"],
            "status" => ["required", "exists:transaction_statuses,name"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ExceptionResponseHelper::throwInvariantError($validator->getMessageBag()->first());
    }
}
