<?php

namespace App\Http\Request;

use App\Http\Request\BaseRequest;

/**
 * Class RegisterUserRequest
 * @package App\Http\Request
 */
class RegisterUserRequest extends BaseRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $pwReg = config('user.passwordRegistrationRegex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).{7,}$/');

        return [
            'name' => 'string|required|min:2|max:100',
            'lastname' => 'string|required|min:2|max:100',
            'email' => 'string|required|email|min:7|max:100|unique:users,email',
            'phone' => 'string|required|regex:/^\+?[1-9]\d{1,14}$/',
            'houseno' => 'string|required',
            'city' => 'string|required',
            'street' => 'string|required',
            'zip' => 'string|required',
            'password_repeat' => 'string|required|min:7|same:password',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:' . $pwReg,
            ],
        ];
    }
}
