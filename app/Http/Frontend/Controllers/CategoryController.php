<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\CategoryRepository as Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    /**
     * CategoryController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * 获取分类列表
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->category->index($request->query('query'));
    }
}
