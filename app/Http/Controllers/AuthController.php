<?php

namespace App\Http\Controllers;

use App\Commands\CheckIpAddress;
use App\Commands\LogoutUser;
use App\Events\UserLoggedInEvent;
use App\Handlers\LoginHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resource\JsonApiResource;
use App\Model\User;
use App\Traits\JsonApiResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\Client as PassportClient;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;

class AuthController extends Controller
{
    use JsonApiResponseTrait;


    /**
     * AuthController constructor.
     */
    public function __construct()
    {

    }

    public function login(LoginRequest $request, LoginHandler $loginHandler): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::whereEmail($email)->firstOrFail();
        $client = PassportClient::where('password_client', 1)
            ->where('revoked', 0)
            ->firstOrFail();

        if ($user->locked) {
            return $this->toJsonApiResponse([], [], ['error' => 'user account not active'], 403);
        }

        $response = $loginHandler->handleOAuthRequest($client, $email, $password);

        if ($response->getStatusCode() !== 200) {
            return $this->toJsonApiResponse([], [], ['error' => 'something went wrong'], 400);
        }

        $user->update(
            [
                'last_login_at' => Carbon::now(),
                'last_login_ip_address' => $request->getClientIp(),
            ]
        );

        event(new UserLoggedInEvent($user));

        return $this->toJsonApiResponse(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param Request $request
     * @return JsonApiResource
     */
    public function logout(Request $request, LogoutUser $logout): JsonApiResource
    {
        try {
            $parsedToken = (new Parser())->parse((string)str_replace('Bearer ', '', $request->header('Authorization')));

            if (!$parsedToken) {
                return new JsonApiResource(['message' => 'user already logged out', 'user' => $request->user()]);
            }

            return new JsonApiResource($logout->run($parsedToken->getHeader('jti')));
        } catch (Exception $e) {
            return new JsonApiResource(null, null, ErrorBag::fromException($e), 400);
        }
    }

    public function check(Request $request, CheckIpAddress $checkIpAddress): JsonResponse
    {
        try {
            $parsedToken = (new Parser())->parse((string)str_replace('Bearer ', '', $request->header('Authorization')));

            if ($parsedToken) {
                /** @var Token $accessToken */
                $accessToken = Token::where('id', $parsedToken->getHeader('jti'))
                    ->where('revoked', 0)
                    ->first();

                if (isset($accessToken)) {
                    $user = $accessToken->user()->first();

                    $passed = true;

                    $doIPcheck = config('nice-auth.login.ipcheck', false);
                    $expirycheck = config('nice-auth.login.expirycheck', true);

                    if ($doIPcheck && !$checkIpAddress->run($user, $request->getClientIp())) {
                        $passed = false;
                    }

                    if ($expirycheck && Carbon::parse($accessToken->expires_at) < Carbon::now()) {
                        $passed = false;
                    }

                    if ($passed) {
                        return $this->toJsonApiResponse(
                            ['message' => 'token valid', 'user' => new UserResource($request->user())]
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->toJsonApiResponse(['message' => 'token invalid'], [], [], 401);
        }

        return $this->toJsonApiResponse(['message' => 'token invalid'], [], [], 401);
    }
}
