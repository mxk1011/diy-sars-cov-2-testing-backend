<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

/**
 * Class BaseEvent
 * @package Nice\Base\Events
 */
abstract class BaseEvent
{
    use InteractsWithSockets;
    use SerializesModels;
}
