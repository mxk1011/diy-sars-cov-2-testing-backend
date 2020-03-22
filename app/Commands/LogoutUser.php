<?php

namespace App\Commands;

use Laravel\Passport\Token;

class LogoutUser
{
	public function run($token)
	{
		/** @var Token $accessToken */
		if ($accessToken = Token::where('id', $token)->first()) {
			$accessToken->revoked = 1;
			$accessToken->save();
		}

		return ['message' => 'logged out'];
	}
}
