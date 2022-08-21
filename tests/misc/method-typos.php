<?php

declare(strict_types=1);

class User
{
    public function getFisrtNameAttribute(): string
    {
        return "{$this->civility} {$this->last_name}";
    }
}
