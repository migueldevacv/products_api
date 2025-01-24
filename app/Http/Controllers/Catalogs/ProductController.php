<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Requests\Catalogs\ProductRequest;
use App\Http\Controllers\Controller;
use App\Providers\MessagesResponse;
use App\Models\Catalogs\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::join('categories as c', 'products.category_id', '=', 'c.id')
            ->select(['products.*', 'c.description as category'])
            ->get();
        return MessagesResponse::indexOk($product);
    }

    public function show(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        return MessagesResponse::showOk($product);
    }

    public function store(ProductRequest $req)
    {
        $data = collect($req->validated())->toArray();
        $data['user_id'] = $req->user()->id;
        $product = Product::create($data);
        return MessagesResponse::createdOk('product', $product);
    }

    public function update(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        $product->update($req->validated());
        return MessagesResponse::updatedOk('product', $product);
    }

    public function destroy(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        $product->update(['status' => !$product->status]);
        return MessagesResponse::disabledOk('product', $product);
    }
}
