<?php

declare(strict_types=1);

class BadRequest
{
    private ?string $name;
    private ?string $alias;
    private ?string $direction;
    private int $timeout;
    private int $maxRepeatNumber;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(?string $direction): void
    {
        $this->direction = $direction;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getMaxRepeatNumber(): int
    {
        return $this->maxRepeatNumber;
    }

    public function setMaxRepeatNumber(int $maxRepeatNumber): void
    {
        $this->maxRepeatNumber = $maxRepeatNumber;
    }
}
