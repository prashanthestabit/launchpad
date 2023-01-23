<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StudentRequest extends FormRequest
{
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
            "name" => "required|min:2|max:191",
            "email" => "required|email|unique:users",
            "address" => "required|min:10|max:255",
            "current_school" => "required|min:2|max:191",
            "previous_school" => "required|min:2|max:191",
            "parents_details" => "required|min:2|max:191",
            "image" => ['required','image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
