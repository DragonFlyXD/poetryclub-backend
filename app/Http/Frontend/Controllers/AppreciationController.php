<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreAppreciation;
use App\Http\Requests\StorePoem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use Illuminate\Http\Request;

class AppreciationController extends Controller
{
    protected $appreciation;

    /**
     * AppreciationController constructor.
     * @param Appreciation $appreciation
     */
    public function __construct(Appreciation $appreciation)
    {
        $this->appreciation = $appreciation;
    }

    /**
     * 获取品鉴列表
     *
     * @return mixed
     */
    public function index()
    {
        return $this->appreciation->index();
    }

    /**
     * 存储品鉴信息
     *
     * @param StoreAppreciation $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(StoreAppreciation $request)
    {
        return $this->appreciation->store($request);
    }

    /**
     * 获取指定品鉴
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($appreciation)
    {
        return $this->appreciation->show($appreciation);
    }

    /**
     * 更新指定品鉴
     *
     * @param StoreAppreciation $request
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(StoreAppreciation $request, $appreciation)
    {
        return $this->appreciation->renew($request, $appreciation);
    }
}
