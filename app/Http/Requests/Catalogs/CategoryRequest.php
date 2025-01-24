<?php

namespace App\Http\Requests\Catalogs;

use App\Http\Requests\NaturalCrudRequest;
use App\Models\Catalogs\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CategoryRequest extends NaturalCrudRequest
{

    protected $_model = 'category';
    protected $_table = 'categories';
    protected $_modelClass = Category::class;

    public function mapResponses(): array
    {
        return [
            Request::METHOD_GET => fn () => self::tryFindId(),
            Request::METHOD_POST => fn () => self::rulesPost(),
            Request::METHOD_PUT => fn () => self::tryFindId(self::rulesPut($this->route()->parameter($this->_model))),
            Request::METHOD_DELETE => fn () => self::tryFindId(),
        ];
    }

    public function rulesPost()
    {
        return [
            'description' => 'required|string|max:20|min:3|unique:' . $this->_table
        ];
    }

    public function rulesPut($id)
    {
        return [
            'description' => [
                'required',
                'string',
                'max:20',
                'min:3',
                Rule::unique($this->_table, 'description')->ignore($id, 'id'),
            ]
        ];
    }
}
