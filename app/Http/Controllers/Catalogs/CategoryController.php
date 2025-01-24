<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\CategoryRequest;
use App\Models\Catalogs\Category;
use App\Providers\MessagesResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return MessagesResponse::indexOk($category);
    }

    public function show(CategoryRequest $req, $id)
    {
        $category = Category::find($id);
        return MessagesResponse::showOk($category);
    }

    public function store(CategoryRequest $req)
    {
        $category = Category::create($req->validated());
        return MessagesResponse::createdOk('category', $category);
    }

    public function update(CategoryRequest $req, $id)
    {
        $category = Category::find($id);
        $category->update($req->validated());
        return MessagesResponse::updatedOk('category', $category);
    }

    public function destroy(CategoryRequest $req, $id)
    {
        $category = Category::find($id);
        $category->update(['status' => !$category->status]);
        return MessagesResponse::disabledOk('category', $category);
    }
}
