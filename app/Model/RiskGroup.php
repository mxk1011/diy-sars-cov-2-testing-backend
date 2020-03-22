<?php

namespace App\Model;

use App\Traits\UuidTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class RiskGroup
{
    use UuidTrait;

    protected $table = 'riskgroups';
}
