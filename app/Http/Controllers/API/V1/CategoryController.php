<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->middleware('auth:api');
        $this->category = $category;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->latest()->paginate(10);

        return $this->sendResponse($categories, 'Category list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $categories = $this->category->pluck('title', 'id');

        return $this->sendResponse($categories, 'Category list');
    }

    public function store(Request $request)
    {
        $tag = $this->category->create(
            [
                'title' => $request->get('title'),
                'type' => $request->get('type'),
                'description' => $request->get('description'),
            ]
        );

        return $this->sendResponse($tag, 'Category Created Successfully');
    }

    public function update(Request $request, $id)
    {
        $tag = $this->category->findOrFail($id);

        $tag->update($request->all());

        return $this->sendResponse($tag, 'Category Information has been updated');
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $category = $this->category->findOrFail($id);

        $category->delete();

        return $this->sendResponse($category, 'Category has been Deleted');
    }
}
