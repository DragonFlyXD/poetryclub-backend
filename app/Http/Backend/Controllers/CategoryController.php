<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreCategory;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\CategoryRepository as Category;

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

    public function index()
    {
        $categories = collect($this->category->index())->toJson();
        return view('admin.category.index', ['categories' => $categories]);
    }

    public function search(Request $request)
    {
        return $this->category->index($request->query('query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategory $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request)
    {
        return $this->category->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->category->show($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param StoreCategory $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategory $request, $id)
    {
        return $this->category->renew($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
