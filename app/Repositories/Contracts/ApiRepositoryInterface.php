<?php

namespace App\Repositories\Contracts;
use Illuminate\Http\Request;

/**
 * API 接口
 *
 * Interface ApiRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface ApiRepositoryInterface
{
    /**
     *
     */
    const CODE_WRONG_ARGS = 'ERR-WRONGARGS';
    /**
     *
     */
    const CODE_NOT_FOUND = 'ERR-NOTFOUND';
    /**
     *
     */
    const CODE_INTERNAL_ERROR = 'ERR-WHOOPS';
    /**
     *
     */
    const CODE_UNAUTHORIZED = 'ERR-UNAUTHORIZED';
    /**
     *
     */
    const CODE_FORBIDDEN = 'ERR-FORBIDDEN';
    /**
     *
     */
    const CODE_UNPROCESSABLE_ENTITY = 'ERR-UNPROCESSABLEENTITY';

    /**
     * 获取状态码
     *
     * @return mixed
     */
    public function getStatusCode();

    /**
     * 设置状态码
     *
     * @param $statusCode
     * @return mixed
     */
    public function setStatusCode($statusCode);

    /**
     * 根据数据类型来产生响应
     *
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respondWith($data, array $headers = []);

    /**
     * 产生响应并处理Collection对象或Eloquent模型
     *
     * @param $item
     * @param array $headers
     * @return mixed
     */
    public function respondWithItem($item, array $headers = []);

    /**
     * 产生响应并处理数组或字符串
     *
     * @param array $array
     * @param array $headers
     * @return mixed
     */
    public function respondWithArray(array $array, array $headers = []);

    /**
     * 产生响应并且返回错误
     *
     * @param $message
     * @param $errorCode
     * @return mixed
     */
    public function respondWithError($message, $errorCode);

    /**
     * 请求不允许
     *
     * @param string $message
     * @return mixed
     */
    public function errorForbidden($message);

    /**
     * 服务器产生内部错误
     *
     * @param string $message
     * @return mixed
     */
    public function errorInternalError($message);

    /**
     * 没有找到指定资源
     *
     * @param string $message
     * @return mixed
     */
    public function errorNotFound($message);

    /**
     * 请求授权失败
     *
     * @param string $message
     * @return mixed
     */
    public function errorUnauthorized($message);

    /**
     * 错误请求
     *
     * @param string $message
     * @return mixed
     */
    public function errorWrongArgs($message);

    /**
     * 无法处理的请求实体
     *
     * @param $message
     * @return mixed
     */
    public function errorUnprocessableEntity($message);

    /**
     * 自定义验证
     *
     * @param Request $request 请求对象
     * @param array $rules 验证规则
     * @param array $messages 错误信息
     * @param array $customAttributes 自定义属性
     * @return mixed
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []);
}