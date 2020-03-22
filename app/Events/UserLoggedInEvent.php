<?php

namespace App\Events;


use App\Events\BaseEvent;
use App\Model\User;

/**
 * Class UserLoggedInEvent
 * @package App\Events
 */
class UserLoggedInEvent extends BaseEvent
{
    protected $user;

    /**
     * UserLoggedInEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}
