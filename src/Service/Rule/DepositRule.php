<?php

namespace App\Service\Rule;

use App\DTO\Operation;

class DepositRule implements FeeRuleInterface
{
    public function supports(Operation $operation): bool
    {
        return $operation->operationType === 'deposit';
    }

    public function calculate(Operation $operation): string
    {
        return $this->roundUp($operation->amount * 0.0003, $operation->currency);
    }

    private function roundUp(float $value, string $currency): string
    {
        $precision = match ($currency) {
            'JPY' => 0,
            default => 2
        };

        return number_format(ceil($value * 10 ** $precision) / 10 ** $precision, $precision, '.', '');
    }
}
