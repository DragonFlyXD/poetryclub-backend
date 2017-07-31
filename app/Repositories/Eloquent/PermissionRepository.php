<?php

namespace App\Repositories\Eloquent;


class PermissionRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Permission';
    }

    /**
     * 创建权限
     *
     * @param $name
     * @param $displayName
     * @param $description
     * @return mixed
     */
    public function createPermission($name, $displayName, $description)
    {
        return $this->create([
            'name' => $name,
            'display_name' => $displayName,
            'description' => $description
        ]);
    }
}