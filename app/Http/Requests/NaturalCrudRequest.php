<?php

namespace App\Http\Requests;

use App\Providers\MessagesResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NaturalCrudRequest extends FormRequest
{
    protected $_model = '';
    protected $_table = '';
    protected $_modelClass = Model::class;

    public function mapResponses()
    {
        return [
            Request::METHOD_GET => fn() => self::tryFindId(),
            Request::METHOD_POST => fn() => self::rulesPost(),
            Request::METHOD_PUT => fn() => self::tryFindId(self::rulesPut($this->route()->parameter($this->_model))),
            Request::METHOD_DELETE => fn() => self::tryFindId(),
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $method = $this->method();
        $responses = $this->mapResponses();
        return $responses[$method]();
    }

    public function rulesGet()
    {
        return [];
    }

    public function rulesPost()
    {
        return [];
    }

    public function rulesPut($id)
    {
        return [];
    }

    public function rulesDelete($id)
    {
        return [];
    }

    protected function tryFindId($extraRules = [])
    {
        if ($this->route()->parameter($this->_model) == null)
            throw new HttpResponseException(MessagesResponse::idNotProvided());
        if (!$this->_modelClass::find($this->route()->parameter($this->_model)))
            throw new HttpResponseException(MessagesResponse::idNotFound());
        return $extraRules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => collect($validator->errors()->all())
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
