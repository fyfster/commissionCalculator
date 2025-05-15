<?php

namespace App\Tests\Service\Rule;

use App\DTO\Operation;
use App\Service\Rule\BusinessWithdrawRule;
use PHPUnit\Framework\TestCase;

class BusinessWithdrawRuleTest extends TestCase
{
    public function testSupportsBusinessWithdraw(): void
    {
        $rule = new BusinessWithdrawRule();
        $operation = new Operation(
            new \DateTimeImmutable('2025-01-01'),
            2,
            'business',
            'withdraw',
            500.00,
            'EUR'
        );

        $this->assertTrue($rule->supports($operation));
    }

    public function testCalculateBusinessWithdrawFee(): void
    {
        $rule = new BusinessWithdrawRule();
        $operation = new Operation(
            new \DateTimeImmutable('2025-01-01'),
            2,
            'business',
            'withdraw',
            500.00,
            'EUR'
        );

        $this->assertSame('2.50', $rule->calculate($operation));
    }
}
