<?php

namespace App\Service\Currency;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private array $rates;

    public function __construct(private HttpClientInterface $client)
    {
        $this->fetchRates();
    }

    private function fetchRates(): void
    {
        // I saw in the Git project comments that the initial link https://api.exchangeratesapi.io/latest
        // was not working so used another one
        $response = $this->client->request('GET', 'https://developers.paysera.com/tasks/api/currency-exchange-rates');
        $data = $response->toArray();

        $this->rates = $data['rates'] ?? [];
        $this->rates['EUR'] ?? 1.0;
    }

    public function convertToEur(float $amount, string $currency): float
    {
        return $amount / $this->rates[$currency];
    }

    public function convertFromEur(float $amount, string $currency): float
    {
        return $amount * $this->rates[$currency];
    }
}
