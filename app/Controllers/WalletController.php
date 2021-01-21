<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\WalletService;
use App\Wallet;
use Exception;

/**
 * Class WalletController
 * @package App\Controllers
 */
class WalletController
{
    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * WalletController constructor.
     */
    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    /**
     * @return Wallet
     */
    public function create(): Wallet
    {
        return new Wallet();
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param string $currency
     * @return Wallet
     * @throws Exception
     */
    public function addMoney(Wallet $wallet, float $amount, string $currency = 'CZK'): Wallet
    {
        return $this->walletService->addMoneyInCurrency($wallet, $amount, $currency);
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param string $currency
     * @return Wallet|bool
     * @throws Exception
     */
    public function subtractMoney(Wallet $wallet, float $amount, string $currency = 'CZK')
    {
        return $this->walletService->subtractMoneyInCurrency($wallet, $amount, $currency);
    }

    /**
     * return total money from account in specified currency
     *
     * @param Wallet $wallet
     * @param string $currency
     * @return float|int
     * @throws Exception
     */
    public function getAllMoneyInCurrency(Wallet $wallet, string $currency )
    {
        return $this->walletService->getAllMoneyInCurrency($wallet, $currency);
    }

    /**
     * transfer money from one currency to another currency
     *
     * @param Wallet $wallet
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return Wallet|bool
     * @throws Exception
     */
    public function transferMoney(Wallet $wallet, string $currencyFrom, string $currencyTo)
    {
        return $this->walletService->transferMoney($wallet, $currencyFrom, $currencyTo);
    }

    /**
     * @param Wallet $wallet
     * @param string $currency
     * @return Wallet
     * @throws Exception
     */
    public function roundMoneyInCurrency(Wallet $wallet, string $currency): Wallet
    {
        return $this->walletService->roundMoney($wallet, $currency);
    }
}
