<?php

namespace App\Service\Rule;

use App\DTO\Operation;

interface FeeRuleInterface
{
    public function supports(Operation $operation): bool;
    public function calculate(Operation $operation): string;
}
