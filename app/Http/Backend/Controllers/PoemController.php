<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StorePoem;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\CategoryRepository as Category;

class PoemController extends Controller
{

    protected $poem;
    protected $category;

    /**
     * PoemController constructor.
     * @param Poem $poem
     * @param Category $category
     */
    public function __construct(Poem $poem, Category $category)
    {
        $this->poem = $poem;
        $this->category = $category;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poems = collect($this->poem->index())->toJson();
        return view('admin.poem.index', ['poems' => $poems]);
    }

    public function search(Request $request)
    {
        return $this->poem->index($request->query("query"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.poem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePoem $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoem $request)
    {
        return $this->poem->store($request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poem = $this->poem->edit($id);
        return view('admin.poem.edit', ['poem' => $poem]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->poem->renew($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->poem->destroy($id);
    }

    public function multipleDestroy(Request $request)
    {
        return $this->poem->destroy($request->all());
    }
}
