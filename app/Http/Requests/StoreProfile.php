<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfile extends FormRequest
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
            'id' => ['nullable', 'integer'],
            'nickname' => ['nullable', 'between:2,20', 'regex:/^[\x{4E00}-\x{9FFF}a-zA-Z0-9_-]{2,20}$/u'],
            'gender' => ['nullable','integer', 'in:0,1,2'],
            'birthday' => ['nullable', 'date'],
            'signature' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:400'],
            'poet' => ['nullable', 'string', 'max:50'],
        ];
    }
}
