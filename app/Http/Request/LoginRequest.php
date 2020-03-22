<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Handlers\LoginHandler;

/**
 * Class LoginRequest
 * @package App\Http\Requests
 */
class LoginRequest extends FormRequest
{
    /**
     * Providing function for ThrottlesLogins trait
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authorize(LoginHandler $loginHandler)
    {
        if (!$loginHandler->checkAuthGuard($this->get('email'), $this->get('password'))) {
            throw ValidationException::withMessages(['invalid credentials'])->status(401);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'secret.2fa' => 'Code not provided or invalid!',
        ];
    }
}
