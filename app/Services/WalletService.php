<?php

declare(strict_types=1);

namespace App\Services;

use App\Library\CurrencyConverter;
use App\Money;
use App\Wallet;
use Exception;

/**
 * Class WalletService
 * @package App\Services
 */
class WalletService
{
    use CurrencyConverter;

    /**
     * if specified currency exist in wallet then add specified amount otherwise create new one with specified currency
     *
     * @param Wallet $wallet
     * @param float $amount
     * @param string $currency
     * @return Wallet
     * @throws Exception
     */
    public function addMoneyInCurrency(Wallet $wallet, float $amount, string $currency): Wallet
    {
        try {
            $money = $wallet->getWalletCurrency($currency);

            if ($money instanceof Money) {
                $money->addAmount($amount);
                return $wallet;
            }

            $newMoney = new Money($amount, $currency);
            $wallet->addMoney($newMoney);

            return $wallet;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param string $currency
     * @return Wallet|bool
     * @throws Exception
     */
    public function subtractMoneyInCurrency(Wallet $wallet, float $amount, string $currency)
    {
        try {
            $money = $wallet->getWalletCurrency($currency);

            if ($money instanceof Money) {
                $money->subtractAmount($amount);

                return $wallet;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param Wallet $wallet
     * @param string $currency
     * @return float|int
     * @throws Exception
     */
    public function getAllMoneyInCurrency(Wallet $wallet, string $currency)
    {
        try {
            $moneyToAdd = (float) 0;

            foreach ($wallet->getWallet() as $money) {
                if ($money->getCurrency() !== $currency) {
                    $rate = $this->getCurrencyRate($currency, $money->getCurrency());
                    $moneyToAdd += $rate * $money->getAmount();
                }
            }

            $moneyInWallet = $wallet->getWalletCurrency($currency);

            if ($moneyInWallet instanceof Money) {

                return $moneyToAdd + $moneyInWallet->getAmount();
            }

            return $moneyToAdd;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param Wallet $wallet
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return Wallet|bool
     * @throws Exception
     */
    public function transferMoney(Wallet $wallet, string $currencyFrom, string $currencyTo)
    {
        try {
            $moneyFrom = $wallet->getWalletCurrency($currencyFrom);
            $moneyTo = $wallet->getWalletCurrency($currencyTo);

            if ($moneyFrom instanceof Money && $moneyTo instanceof Money){
                $rate = $this->getCurrencyRate($currencyTo, $currencyFrom);
                $moneyTo->addAmount($rate * $moneyFrom->getAmount());
                $moneyFrom->subtractAmount($moneyFrom->getAmount());

                return $wallet;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param Wallet $wallet
     * @param string $currency
     * @return Wallet
     * @throws Exception
     */
    public function roundMoney(Wallet $wallet, string $currency): Wallet
    {
        try {
            $currencyMoney = $wallet->getWalletCurrency($currency);
            $currencyMoney->roundAmount();

            return $wallet;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}