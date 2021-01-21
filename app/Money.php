<?php

declare(strict_types=1);

namespace App;

/**
 * Class Money
 * @package App
 */
class Money
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * Money constructor.
     * @param float $amount
     * @param string $currency
     */
    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param float $amount
     */
    public function addAmount(float $amount): void
    {
        $this->amount += $amount;
    }

    /**
     * @param float $amount
     */
    public function subtractAmount(float $amount): void
    {
        $this->amount -= $amount;
    }

    public function roundAmount(): void
    {
        $this->amount = round($this->amount);
    }
}