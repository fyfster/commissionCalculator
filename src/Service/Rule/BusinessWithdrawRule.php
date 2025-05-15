<?php

namespace App\Service\Rule;

use App\DTO\Operation;

class BusinessWithdrawRule implements FeeRuleInterface
{
    public function supports(Operation $operation): bool
    {
        return $operation->userType === 'business' && $operation->operationType === 'withdraw';
    }

    public function calculate(Operation $operation): string
    {
        return $this->roundUp($operation->amount * 0.005, $operation->currency);
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
