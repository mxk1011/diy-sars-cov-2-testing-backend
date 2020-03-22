<?php

namespace App\Commands;

use App\Model\User;

/**
 * Class CheckIpAddress
 * @package App\Commands
 */
class CheckIpAddress
{
    /**
     * @param User|null $user
     * @param null|string $ipAddress
     * @return bool
     */
    public function run(?User $user = null, ?string $ipAddress = null): bool
    {
        if (is_null($user) || is_null($ipAddress)) {
            return false;
        }

        return $user->last_login_ip_address === $ipAddress;
    }
}
