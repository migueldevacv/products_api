<?php

namespace App\Http\Requests\Catalogs;

use App\Http\Requests\NaturalCrudRequest;
use App\Models\Admin\Role;
use App\Models\Catalogs\Product;
use App\Rules\Equals;
use Closure;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductRequest extends NaturalCrudRequest
{

    protected $_model = 'product';
    protected $_table = 'products';
    protected $_modelClass = Product::class;

    public function mapResponses(): array
    {
        return [
            Request::METHOD_GET => fn() => self::tryFindId(),
            Request::METHOD_POST => fn() => self::rulesPost(),
            Request::METHOD_PUT => fn() => self::tryFindId(self::rulesPut($this->route()->parameter($this->_model), $this->_model)),
            Request::METHOD_DELETE => fn() => self::tryFindId(self::rulesDelete($this->route()->parameter($this->_model), $this->_model)),
        ];
    }

    public function rulesPost()
    {
        return [
            'name' => 'required|string|max:50|min:5',
            'description' => 'required|string',
            // 'image' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function rulesPut($id, $model = null)
    {
        $user =  $this->user();
        return [
            'name' => 'required|string|max:50|min:5',
            'description' => 'required|string',
            // 'image' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'user.id' => [
                function ($attribute, $value, Closure $fail) use ($id, $model, $user) {
                    $product = Product::find($id);
                    if ($product->user_id != $user->id && $user->role_id != Role::ADMIN)
                        $fail("The {$model} must be created by the user.");
                },
            ],
        ];
    }

    public function rulesDelete($id, $model = null)
    {
        $user =  $this->user();
        return [
            'user.id' => [
                function ($attribute, $value, Closure $fail) use ($id, $model, $user) {
                    $product = Product::find($id);
                    if ($product->user_id != $user->id && $user->role_id != Role::ADMIN)
                        $fail("The {$model} must be created by the user.");
                },
            ],
        ];
    }
}
