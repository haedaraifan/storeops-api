<?php

namespace App\Http\Requests;

use App\Helpers\ExceptionResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            "email" => ["nullable", "max:100", "email"],
            "name" => ["nullable", "max:100"],
            "password" => ["nullable", "max:100"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ExceptionResponseHelper::throwInvariantError($validator->getMessageBag()->first());
    }
}
