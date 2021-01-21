<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Controllers\WalletController;
use App\Wallet;
use App\Money;

/**
 * Class WalletTest
 */
class WalletTest extends TestCase
{
    private $walletController;
    private $wallet;

    public function setUp(): void
    {
        parent::setUp();
        $this->wallet = new Wallet();
        $this->walletController = new WalletController();
    }

    public function test_get_money_in_currency()
    {
        $response = $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->assertInstanceOf(Wallet::class, $response);
        $money = $response->getWalletCurrency('CZK');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(100, $money->getAmount());
    }

    public function test_get_money_in_currency_with_empty_wallet()
    {
        $this->assertNull($this->wallet->getWalletCurrency('EUR'));
    }

    public function test_add_money_in_currency()
    {
        $response = $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->assertInstanceOf(Wallet::class, $response);
        $money = $response->getWalletCurrency('CZK');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(100, $money->getAmount());
    }

    public function test_add_money_in_existing_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $response = $this->walletController->addMoney($this->wallet, 50, 'CZK');
        $this->assertInstanceOf(Wallet::class, $response);
        $money = $response->getWalletCurrency('CZK');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(150, $money->getAmount());
    }

    public function test_subtract_money_in_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $response = $this->walletController->subtractMoney($this->wallet, 50, 'CZK');
        $this->assertInstanceOf(Wallet::class, $response);
        $money = $response->getWalletCurrency('CZK');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(50, $money->getAmount());
    }

    public function test_subtract_money_in_non_existing_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $response = $this->walletController->subtractMoney($this->wallet, 50, 'EUR');
        $this->assertFalse($response);
    }

    public function test_get_all_money_in_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->walletController->addMoney($this->wallet, 100, 'EUR');
        $response = $this->walletController->getAllMoneyInCurrency($this->wallet, 'CZK');
        $this->assertIsFloat($response);
    }

    public function test_get_all_money_in_currency_not_in_wallet()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->walletController->addMoney($this->wallet, 100, 'EUR');
        $response = $this->walletController->getAllMoneyInCurrency($this->wallet, 'USD');
        $this->assertIsFloat($response);
    }

    public function test_transfer_money_to_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->walletController->addMoney($this->wallet, 100, 'EUR');
        $response = $this->walletController->transferMoney($this->wallet, 'EUR', 'CZK');
        $this->assertInstanceOf(Wallet::class, $response);
        $money = $response->getWalletCurrency('EUR');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(0, $money->getAmount());
    }

    public function test_invalid_transfer_money_to_currency()
    {
        $this->walletController->addMoney($this->wallet, 100, 'CZK');
        $this->walletController->addMoney($this->wallet, 100, 'EUR');
        $response = $this->walletController->transferMoney($this->wallet, 'USD', 'CZK');
        $this->assertFalse($response);
    }

    public function test_round_currency_money()
    {
        $this->walletController->addMoney($this->wallet, 100.57, 'CZK');
        $responseCzk = $this->walletController->roundMoneyInCurrency($this->wallet, 'CZK');
        $this->assertInstanceOf(Wallet::class, $responseCzk);
        $moneyCzk = $responseCzk->getWalletCurrency('CZK');
        $this->assertInstanceOf(Money::class, $moneyCzk);
        $this->assertEquals(101, $moneyCzk->getAmount());

        $this->walletController->addMoney($this->wallet, 100.17, 'EUR');
        $responseEur = $this->walletController->roundMoneyInCurrency($this->wallet, 'EUR');
        $this->assertInstanceOf(Wallet::class, $responseEur);
        $moneyEur = $responseEur->getWalletCurrency('EUR');
        $this->assertInstanceOf(Money::class, $moneyEur);
        $this->assertEquals(100, $moneyEur->getAmount());
    }
}