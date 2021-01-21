<?php

declare(strict_types=1);

namespace App;

/**
 * Class Wallet
 * @package App
 */
class Wallet
{
    /**
     * @var array
     */
    public $account = array();

    /**
     * @param string $currency
     * @return Money|null
     */
    public function getWalletCurrency(string $currency): ?Money
    {
        if (!array_key_exists($currency, $this->account)) {

            return null;
        }

        return $this->account[$currency];
    }

    /**
     * @param Money $money
     */
    public function addMoney(Money $money): void
    {
        $this->account[$money->getCurrency()] = $money;
    }

    /**
     * returns wallet data
     *
     * @return array
     */
    public function getWallet(): array
    {
        return $this->account;
    }
}