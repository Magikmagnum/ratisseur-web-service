<?php

namespace App\DTO;

class RegisterDTO
{
    public function __construct(public readonly int $limit)
    {
    }
}
