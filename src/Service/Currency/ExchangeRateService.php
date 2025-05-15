<?php

namespace App\Service\Currency;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private array $rates;

    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey
    ) {
        $this->fetchRates();
    }

    private function fetchRates(): void
    {
        // I saw in the Git project comments that the initial link https://api.exchangeratesapi.io/latest
        // was not working so used another one from the comments in the alternitive issue
        $response = $this->client->request('GET', 'https://api.apilayer.com/exchangerates_data/latest?base=EUR', [
            'headers' => [
                'Content-Type' => 'text/plain',
                'apikey' => $this->apiKey,
            ],
        ]);
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
