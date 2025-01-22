<?php

namespace App\Providers;

use Illuminate\Http\Response;

class MessagesResponse
{

    public static function authOk($data = [])
    {
        return response()->json([
            'message' => "Login successful",
            'status' => true,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public static function sessionExpired()
    {
        return response()->json([
            'message' => "You're session was expired",
            'errors' => ["You're session was expired"],
            'status' => false
        ], Response::HTTP_FORBIDDEN);
    }

    public static function notLogged()
    {
        return response()->json([
            'message' => "You are not logged",
            'errors' => ["You are not logged"],
            'status' => false
        ], Response::HTTP_FORBIDDEN);
    }

    public static function authFail()
    {
        return response()->json([
            'message' => "The credentials does not match",
            'errors' => ["The credentials does not match"],
            'status' => false
        ], Response::HTTP_BAD_REQUEST);
    }

    public static function indexOk($data = [])
    {
        return response()->json([
            'data' => $data,
            'status' => true
        ], Response::HTTP_OK);
    }

    public static function showOk($data = [])
    {
        return response()->json([
            'data' => $data,
            'status' => true
        ], Response::HTTP_OK);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public static function createdOk($model = '', $data = [])
    {
        return response()->json([
            'data' => $data,
            'message' => "The $model was created successfully",
            'status' => true
        ], Response::HTTP_CREATED);
    }

    public static function updatedOk($model = '', $data = [])
    {
        return response()->json([
            'data' => $data,
            'message' => "The $model was updated successfully",
            'status' => true
        ], Response::HTTP_OK);
    }

    public static function disabledOk($model = '', $data = [])
    {
        return response()->json([
            'data' => $data,
            'message' => "The status form $model was changed successfully",
            'status' => true
        ], Response::HTTP_OK);
    }

    public static function idNotProvided()
    {
        return response()->json([
            'status' => false,
            'message' => 'You need to send the id',
            'errors' => [
                'The id was not provided'
            ]
        ], Response::HTTP_NOT_FOUND);
    }

    public static function idNotFound()
    {
        return response()->json([
            'status' => false,
            'message' => 'Register not found',
            'errors' => [
                'The id provided was not found'
            ]
        ], Response::HTTP_NOT_FOUND);
    }
}
