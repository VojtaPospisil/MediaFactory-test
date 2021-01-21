<?php

declare(strict_types=1);

namespace App\Library;

/**
 * Trait CurrencyConverter
 * @package App\Library
 */
trait CurrencyConverter
{
    /**
     * Gets api currency data
     *
     * @param string $currencyTo
     * @param string $currencyFrom
     * @return mixed
     */
    public function getCurrencyRate(string $currencyTo, string $currencyFrom)
    {
        $req_url = 'https://v6.exchangerate-api.com/v6/df3e880363ed2a819791cfc2/latest/' . $currencyFrom;
        $response_json = file_get_contents($req_url);
        $response = json_decode($response_json);
        $rate = (array) $response->conversion_rates;

        return $rate[$currencyTo];
    }
}

