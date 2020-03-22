<?php

namespace App\Http\Controllers;

use App\Commands\RegisterUser;
use App\Http\Resource\JsonApiResource;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Request\RegisterUserRequest;
use App\Http\Resource\Producer\UserResource;

class UserController extends Controller
{

    public function register(RegisterUserRequest $request, RegisterUser $registerUser)
    {
        try {
            $user = $registerUser->run(
                $request->get('name'),
                $request->get('lastname'),
                $request->get('email'),
                $request->get('password'),
                $request->get('phone'),
                $request->get('street'),
                $request->get('houseno'),
                $request->get('zip'),
                $request->get('city'),
                User::ROLE_PATIENT
            );

            return new UserResource($user, [], [], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonApiResource(null, [], ['message' => [$e->getMessage()]], 422);
        } catch (\Exception $e) {
            return new JsonApiResource(null, [], [], 400);
        }
    }
}
