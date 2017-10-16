<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoem extends FormRequest
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
            'category' => ['required', 'numeric'],
            'title' => ['required', 'between:1,50'],
            'body' => ['required'],
            'dynamicTags' => ['array', 'between:0,5']
        ];
    }

    public function messages()
    {
        return [
            'dynamicTags.array' => '标签类型错误。',
            'dynamicTags.between' => '标签数量必须介于0-5个之间。'
        ];
    }
}
