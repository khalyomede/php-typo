<?php

declare(strict_types=1);

class Point
{
    public function __construct(public readonly float $x, public readonly float $y)
    {
    }

    public function __toString()
    {
        return "{$this->x} {$this->y}";
    }
}
