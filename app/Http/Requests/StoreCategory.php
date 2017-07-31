<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategory extends FormRequest
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
            'name' => ['required', 'between:1,50', 'unique:categories']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '分类名不能为空。',
            'name.between' => '分类名必须介于1-50个字符之间。',
            'name.unique' => '分类名已存在。'
        ];
    }
}
