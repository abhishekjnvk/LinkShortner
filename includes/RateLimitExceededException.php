<?php

class RateLimitExceededException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $retryAfter,
        private readonly int $limit
    ) {
        parent::__construct($message);
    }

    public function retryAfter(): int
    {
        return $this->retryAfter;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
