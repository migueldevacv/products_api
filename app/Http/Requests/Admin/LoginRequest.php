<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\NaturalCrudRequest;
use App\Models\Admin\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Providers\MessagesResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends NaturalCrudRequest
{

    protected $_model = 'user';
    protected $_table = 'users';
    protected $_modelClass = User::class;

    public function authenticate(): User|null
    {
        $user = User::where('email', $this->input('email'))
            ->where('status', true)
            ->first();

        if (!$user || !Hash::check($this->input('password'), $user->password))
            throw new HttpResponseException(MessagesResponse::authFail());

        return $user;
    }

    public function mapResponses(): array
    {
        return [Request::METHOD_POST => fn() => self::rulesPost()];
    }

    public function rulesPost()
    {
        return [
            'email' => 'required|string|max:100|min:8',
            'password' => 'required|string|max:20|min:7',
        ];
    }

    public function rulesPut($id)
    {
        return [
            'password' => 'required|string|max:20|min:7',
            'email' => [
                'required',
                'string',
                'max:100',
                'min:8',
            ]
        ];
    }
}
