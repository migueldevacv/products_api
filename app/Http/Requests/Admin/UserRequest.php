<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\NaturalCrudRequest;
use App\Models\Admin\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserRequest extends NaturalCrudRequest
{

    protected $_model = 'user';
    protected $_table = 'users';
    protected $_modelClass = User::class;


    public function mapResponses(): array
    {
        return [
            Request::METHOD_GET => fn() => self::tryFindId(),
            Request::METHOD_POST => fn() => self::rulesPost(),
            Request::METHOD_PUT => fn() => self::tryFindId(self::rulesPut($this->route()->parameter($this->_model))),
            Request::METHOD_DELETE => fn() => self::tryFindId(),
        ];
    }

    public function rulesPost()
    {
        return [
            'email' => 'required|string|max:100|min:8|unique:' . $this->_table,
            'password' => 'required|string|max:20|min:7',
            // 'c_password' => 'required|string|max:20|min:7|same:password',
            'name' => 'required|string|max:50|min:4',
            'role_id' => 'exists:roles,id',
        ];
    }

    public function rulesPut($id)
    {
        return [
            'password' => 'required|string|max:20|min:7',
            // 'c_password' => 'required|string|max:20|min:7|same:password',
            'name' => 'required|string|max:50|min:4',
            'role_id' => 'required|exists:roles,id',
            'email' => [
                'required',
                'string',
                'max:100',
                'min:8',
                Rule::unique($this->_table, 'email')->ignore($id, 'id'),
            ]
        ];
    }
}
