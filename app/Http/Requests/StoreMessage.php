<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessage extends FormRequest
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
            'body' => ['required', 'min:6'],
            'user' => ['required', 'numeric', 'not_in:' . id()]
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '私信内容不能为空。',
            'body.min' => '私信内容至少为6个字符。',
            'user.required' => '收信人ID不能为空。',
            'user.numeric' => '收信人ID必须为数字。',
            'user.different' => '收信人不能为自己。'
        ];
    }
}
