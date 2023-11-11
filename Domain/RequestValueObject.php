<?php

declare(strict_types=1);

class RequestValueObject
{
    private string $body;

    private int $code;

    public function __construct(string $body, int $code)
    {
        $this->body = $body;
        $this->code = $code;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }
}
