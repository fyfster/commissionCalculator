<?php

namespace App\Command;

use App\DTO\Operation;
use App\Service\Calculator\CommissionCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:calculate-commission')]
class CalculateCommissionCommand extends Command
{
    public function __construct(private CommissionCalculator $calculator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Calculates commission fees from a CSV file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the input CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $output->writeln("<error>File not found: $file</error>");
            return Command::FAILURE;
        }

        $handle = fopen($file, 'r');
        $lineNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            if (count($row) !== 6) {
                $output->writeln("<error>Invalid format on line $lineNumber: Expected 6 fields, got " . count($row) . "</error>");
                continue;
            }

            [$date, $userId, $userType, $operationType, $amount, $currency] = $row;

            try {
                $operation = new Operation(
                    new \DateTimeImmutable($date),
                    (int) $userId,
                    $userType,
                    $operationType,
                    (float) $amount,
                    $currency
                );

                $fee = $this->calculator->calculate($operation);
                $output->writeln($fee);
            } catch (\Throwable $e) {
                $output->writeln("<error>Error processing line $lineNumber: {$e->getMessage()}</error>");
            }
        }

        fclose($handle);
        return Command::SUCCESS;
    }
}
