<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
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
            'name' => ['required', 'between:2,20', 'unique:users', 'regex:/^[\x{4E00}-\x{9FFF}a-zA-Z0-9_-]{2,20}$/u'],
            'password' => ['required', 'between:6,20', 'regex:/^[a-zA-Z0-9_-]{6,20}$/', 'confirmed'],
            'email' => ['required_without:mobile', 'max:50', 'unique:users', 'regex:/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/'],
            'mobile' => ['sometimes', 'required', 'unique:users', 'regex:/^1\d{10}$/'],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'protocol' => ['accepted'],
            'is_submit' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'is_submit.required' => '防止AJAX验证数据而误将数据保存进数据库。'
        ];
    }
}
