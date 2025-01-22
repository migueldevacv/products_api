<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Admin\User;
use App\Providers\MessagesResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function login(LoginRequest $req)
    {
        $user = $req->authenticate();
        $token = JWTAuth::fromUser($user);
        $resBody = ['user' => $user, 'token' => $token];
        return MessagesResponse::authOk($resBody);
    }

    public function register(UserRequest $req)
    {
        $data = collect($req->validated())->except('role_id');
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data->toArray());
        $token = JWTAuth::fromUser($user);
        $resBody = ['user' => $user, 'token' => $token];
        return MessagesResponse::authOk($resBody);
    }

    public function index()
    {
        $user = User::all();
        return MessagesResponse::indexOk($user);
    }

    public function show($id)
    {
        $user = User::find($id);
        return MessagesResponse::showOk($user);
    }

    public function store(UserRequest $req)
    {
        $data = $req->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return MessagesResponse::createdOk('user', $user);
    }

    public function update(UserRequest $req, $id)
    {
        $data = $req->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::find($id);
        $user->update($data);
        return MessagesResponse::updatedOk('user', $user);
    }

    public function destroy(UserRequest $req, $id)
    {
        $user = User::find($id);
        $user->update(['status' => !$user->status]);
        return MessagesResponse::disabledOk('user', $user);
    }
}
