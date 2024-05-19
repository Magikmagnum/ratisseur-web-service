<?php

namespace App\DTO;

final class IdentityDTO
{
    public function __construct(public readonly int $limit)
    {
    }
}
