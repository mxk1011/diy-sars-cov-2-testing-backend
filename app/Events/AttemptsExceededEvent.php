<?php

namespace App\Events;

/**
 * Class AttemptsExceededEvent
 * @package App\Events
 */
class AttemptsExceededEvent extends BaseEvent
{
    protected $maxAttempts;

    protected $requestData;

    /**
     * AttemptsExceededEvent constructor.
     * @param array $requestData
     */
    public function __construct(int $maxAttempts, array $requestData)
    {
        $this->maxAttempts = $maxAttempts;
        $this->requestData = $requestData;
    }

    /**
     * @return int|null
     */
    public function getMaxAttempts(): ?int
    {
        return $this->maxAttempts;
    }

    /**
     * @return array|null
     */
    public function getRequestData(): ?array
    {
        return $this->requestData;
    }
}
