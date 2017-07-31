<?php

namespace App\Repositories\Contracts;

/**
 * Repository 接口
 *
 * Interface RepositoryInterface
 * @package App\Repositories\Contracts
 */
interface RepositoryInterface
{
    /*
    |--------------------------------------------------------------------------
    | 数据库相关
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */
    /**
     * 根据主键查找数据
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * 根据指定指定键与值查找数据
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'));


    /**
     * 获取所有数据
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'));

    /**
     * 预加载
     *
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * 批量创建
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * 根据主键批量更新
     *
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id');

    /**
     * 根据主键删除数据
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * 获取分页数据
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 10, $columns = array('*'));
}