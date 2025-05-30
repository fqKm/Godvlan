<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserRegRequest extends FormRequest
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
            'email'=>['required','max:100'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'
            ],
            'name'=>['required','max:100'],
            'company'=>['required','max:100'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors"=>$validator->getMessageBag()
        ],400));
    }
}
