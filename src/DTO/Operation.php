<?php

namespace App\DTO;

class Operation
{
    public function __construct(
        public \DateTimeImmutable $date,
        public int $userId,
        public string $userType,
        public string $operationType,
        public float $amount,
        public string $currency
    ) {}
}
