<?php

namespace App\Http\Requests;

use App\Http\Frontend\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRole extends FormRequest
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
        // 获取待验证 or 待更新的RoleId
        if (request()->getMethod() === 'POST') {
            $roleId = Role::where('name', request()->get('name'))->first()->id;
        } else {
            $roleId = intval(request()->route('role'));
        }
        return [
            'name' => [
                'required',
                'between:1,50',
                Rule::unique('roles')->ignore($roleId),
                'regex:/^[\x{4E00}-\x{9FFF}a-zA-Z0-9_-]{1,50}$/u'
            ],
            'perms' => ['nullable', 'array'],
            'display_name' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:191'],
            'is_submit' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'display_name.string' => '显示名必须为字符串。',
            'display_name.max' => '显示名至多为50个字符',
            'description.string' => '简述必须为字符串',
            'description.max' => '简述至多为191个字符',
            'is_submit.required' => '防止AJAX验证数据而误将数据保存进数据库。'
        ];
    }
}
