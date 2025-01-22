<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\NaturalCrudRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Admin\Role;

class RoleRequest extends NaturalCrudRequest
{
    protected $_model = 'role';
    protected $_table = 'roles';
    protected $_modelClass = Role::class;

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
