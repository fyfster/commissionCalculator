<?php

namespace App\Service\Rule;

use App\DTO\Operation;
use App\Service\Tracker\UserTransactionTracker;
use App\Service\Currency\ExchangeRateService;

class PrivateWithdrawRule implements FeeRuleInterface
{
    public function __construct(
        private UserTransactionTracker $tracker,
        private ExchangeRateService $exchangeRateService
    ) {}

    public function supports(Operation $operation): bool
    {
        return $operation->userType === 'private' && $operation->operationType === 'withdraw';
    }

    public function calculate(Operation $operation): string
    {
        $amountInEur = $this->exchangeRateService->convertToEur($operation->amount, $operation->currency);
        $freeLimitLeft = $this->tracker->getWeeklyFreeLimitLeft($operation);

        $taxable = max(0, $amountInEur - $freeLimitLeft);
        $this->tracker->recordOperation($operation, $amountInEur);

        $feeInEur = $taxable * 0.003;
        $feeInOriginalCurrency = $this->exchangeRateService->convertFromEur($feeInEur, $operation->currency);

        return $this->roundUp($feeInOriginalCurrency, $operation->currency);
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
