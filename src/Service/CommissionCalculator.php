<?php

namespace App\Service\Calculator;

use App\DTO\Operation;
use App\Service\Rule\FeeRuleInterface;

class CommissionCalculator
{
    /**
     * @param iterable<FeeRuleInterface> $rules
     */
    public function __construct(private iterable $rules) {}

    public function calculate(Operation $operation): string
    {
        foreach ($this->rules as $rule) {
            if ($rule->supports($operation)) {
                return $rule->calculate($operation);
            }
        }

        throw new \RuntimeException('No applicable commission rule found for the operation.');
    }
}
