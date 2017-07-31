<?php

namespace App\Repositories\Eloquent;


class RoleRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Role';
    }

    /**
     * 创建角色
     *
     * @param $name
     * @param $displayName
     * @param $description
     * @return mixed
     */
    public function createRole($name, $displayName, $description)
    {
        return $this->create([
            'name' => $name,
            'display_name' => $displayName,
            'description' => $description
        ]);
    }
}