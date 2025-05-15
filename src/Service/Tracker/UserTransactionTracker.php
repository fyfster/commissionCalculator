<?php

namespace App\Service\Tracker;

use App\DTO\Operation;

class UserTransactionTracker
{
    private array $history = [];

    public function getWeeklyFreeLimitLeft(Operation $operation): float
    {
        $week = $operation->date->format('o-W');
        $key = $operation->userId . '-' . $week;

        $used = $this->history[$key]['amount'] ?? 0;
        $count = $this->history[$key]['count'] ?? 0;

        if ($count >= 3) {
            return 0.0;
        }

        return max(0, 1000.0 - $used);
    }

    public function recordOperation(Operation $operation, float $amountInEur): void
    {
        $week = $operation->date->format('o-W');
        $key = $operation->userId . '-' . $week;

        $this->history[$key]['amount'] = ($this->history[$key]['amount'] ?? 0) + $amountInEur;
        $this->history[$key]['count'] = ($this->history[$key]['count'] ?? 0) + 1;
    }
}
