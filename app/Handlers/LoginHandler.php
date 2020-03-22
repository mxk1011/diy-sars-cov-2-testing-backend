<?php

namespace App\Handlers;


use GuzzleHttp\Client;
use Laravel\Passport\Client as PassportClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Class LoginHandler
 * @package App\Handlers
 */
class LoginHandler
{
    protected $httpClient;

    /**
     * LoginHandler constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $guard
     * @return bool
     */
    public function checkAuthGuard(string $email, string $password, string $guard = 'web'): bool
    {
        return (auth()->guard($guard)->attempt(['email' => $email, 'password' => $password]));
    }

    /**
     * @param PassportClient $client
     * @param string $email
     * @param string $password
     * @return ResponseInterface
     */
    public function handleOAuthRequest(PassportClient $client, string $email, string $password): ResponseInterface
    {
        return $this->httpClient
            ->post(
                $this->getOAuthUrl(),
                $this->buildOAuthRequest($client, $email, $password)
            );
    }

    /**
     * @return string
     */
    private function getOAuthUrl(): string
    {
        return env('APP_URL').'/oauth/token';
    }

    /**
     * @param PassportClient $client
     * @param string $email
     * @param string $password
     * @return array
     */
    private function buildOAuthRequest(PassportClient $client, string $email, string $password): array
    {
        return [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ],
        ];
    }
}
