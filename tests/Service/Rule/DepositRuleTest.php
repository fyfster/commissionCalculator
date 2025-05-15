<?php

namespace App\Tests\Service\Rule;

use App\DTO\Operation;
use App\Service\Rule\DepositRule;
use PHPUnit\Framework\TestCase;

class DepositRuleTest extends TestCase
{
    public function testSupportsDepositOperation(): void
    {
        $rule = new DepositRule();
        $operation = new Operation(
            new \DateTimeImmutable('2025-01-01'),
            1,
            'private',
            'deposit',
            1000.00,
            'EUR'
        );

        $this->assertTrue($rule->supports($operation));
    }

    public function testCalculateCorrectFee(): void
    {
        $rule = new DepositRule();
        $operation = new Operation(
            new \DateTimeImmutable('2025-01-01'),
            1,
            'private',
            'deposit',
            1000.00,
            'EUR'
        );

        $this->assertSame('0.30', $rule->calculate($operation));
    }
}
