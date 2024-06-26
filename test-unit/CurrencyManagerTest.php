<?php

namespace Pantagruel74\MulticurtestCurrencyManagerTest;

use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Pantagruel74\MulticurtestCurrencyManager\CurrencyManager;
use Pantagruel74\MulticurtestCurrencyManager\value\AmountInCurrencyVal;
use Pantagruel74\MulticurtestCurrencyManagerStubs\managers\CurrencyConvMultiplierManagerStub;
use Pantagruel74\MulticurtestCurrencyManagerStubs\managers\CurrencyDefManagerStub;
use Pantagruel74\MulticurtestCurrencyManagerStubs\records\CurrencyConvMultiplierRecStub;
use Pantagruel74\MulticurtestCurrencyManagerStubs\records\CurrencyDefRecStub;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

/**
 * Test-case of CurrencyManager for phpunit.
 */
class CurrencyManagerTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetAllCurrenciesExists()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $allCurIds = $currencyManager->getAllCurrenciesExists();
        $this->assertEquals(["RUB", "EUR"], $allCurIds);
    }

    /**
     * @return void
     */
    public function testConvertCurrencyNameToValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $convertedName = $currencyManager->convertNameToNewCurrencyToValid("boBa");
        $this->assertEquals("BOBA", $convertedName);
    }

    /**
     * @return void
     */
    public function testConvertCurrencyNameToValidInvalid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $convertedName = $currencyManager->convertNameToNewCurrencyToValid("boBa12");
    }

    /***
     * @return void
     */
    public function testConvertAmountToValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR",
                80, (new \DateTime())->getTimestamp() - 1000),
            new CurrencyConvMultiplierRecStub("EUR", "RUB",
                100, (new \DateTime())->getTimestamp() + 1000),
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $result = $currencyManager->convertAmountTo(
            new AmountInCurrencyVal(657, 0, "RUB"),
            "EUR"
        );
        $this->assertEquals("EUR", $result->getCurId());
        $this->assertEquals(6, $result->getDecades());
    }

    /**
     * @return void
     */
    public function testConvertAmountToInvalidArgument()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR",
                80, (new \DateTime())->getTimestamp() - 1000),
            new CurrencyConvMultiplierRecStub("EUR", "RUB",
                100, (new \DateTime())->getTimestamp() + 1000),
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $result = $currencyManager->convertAmountTo(
            new AmountInCurrencyVal(657, 0, "RUB"),
            "RUB"
        );
    }

    /**
     * @return void
     */
    public function testSetConvertionMultiplierValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR",
                80, (new \DateTime())->getTimestamp() - 1000),
            new CurrencyConvMultiplierRecStub("EUR", "RUB",
                0.01, (new \DateTime())->getTimestamp() - 1),
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $currencyManager->setConversionMultiplier(
            "RUB",
            new CurrencyConversionMultiplierVal("EUR", 120)
        );
        $result = $currencyManager->convertAmountTo(
            new AmountInCurrencyVal(10, 0, "RUB"),
            "EUR"
        );
        $this->assertEquals("EUR", $result->getCurId());
        $this->assertEquals(1200, $result->getDecades());
    }

    /**
     * @return void
     */
    public function testSetConvertionMultiplierInvalid1()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR",
                80, (new \DateTime())->getTimestamp() - 1000),
            new CurrencyConvMultiplierRecStub("EUR", "RUB",
                0.01, (new \DateTime())->getTimestamp() - 1),
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $currencyManager->setConversionMultiplier(
            "EUR",
            new CurrencyConversionMultiplierVal("EUR", 120)
        );
    }

    /**
     * @return void
     */
    public function testSetConvertionMultiplierInvalid2()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR",
                80, (new \DateTime())->getTimestamp() - 1000),
            new CurrencyConvMultiplierRecStub("EUR", "RUB",
                0.01, (new \DateTime())->getTimestamp() - 1),
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $currencyManager->setConversionMultiplier(
            "EUR",
            new CurrencyConversionMultiplierVal("BOB", 120)
        );
    }

    /**
     * @return void
     */
    public function testAddCurrencyValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $currencyManager->addCurrency(
            "USD", [
                new CurrencyConversionMultiplierVal("RUB", 0.1),
                new CurrencyConversionMultiplierVal("EUR", 1.1),
            ],
            1
        );
        $this->assertEquals(["RUB", "EUR", "USD"],
            $currencyManager->getAllCurrenciesExists());
        $conversResult = $currencyManager->convertAmountTo(
            new AmountInCurrencyVal(10, 0, "EUR"),
            "USD"
        );
        $this->assertEquals(90, $conversResult->getDecades());
        $this->assertEquals(1, $conversResult->getDotPosition());
        $this->assertEquals("USD", $conversResult->getCurId());
    }

    /**
     * @return void
     */
    public function testAddCurrencyInvalid1()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $currencyManager->addCurrency(
            "RUB", [
            new CurrencyConversionMultiplierVal("RUB", 0.1),
            new CurrencyConversionMultiplierVal("EUR", 1.1),
        ],
            1
        );
    }

    /**
     * @return void
     */
    public function testAddCurrencyInvalid2()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $currencyManager->addCurrency(
            "USD", [
            new CurrencyConversionMultiplierVal("RUB", 0.1),
        ],
            1
        );
    }

    /**
     * @return void
     */
    public function testGetZeroForCurrencyValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 1, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $zeroRub = $currencyManager->getZeroForCurrency("RUB");
        $this->assertEquals("RUB", $zeroRub->getCurId());
        $this->assertEquals(0, $zeroRub->getDecades());
        $this->assertEquals(0, $zeroRub->getDotPosition());
        $zeroEur = $currencyManager->getZeroForCurrency("EUR");
        $this->assertEquals("EUR", $zeroEur->getCurId());
        $this->assertEquals(0, $zeroEur->getDecades());
        $this->assertEquals(1, $zeroEur->getDotPosition());
    }

    /**
     * @return void
     */
    public function testGetZeroForCurrencyInvalid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 1, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
            new CurrencyConvMultiplierRecStub("RUB", "EUR", 100)
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $zeroBob = $currencyManager->getZeroForCurrency("BOB");
    }

    /**
     * @return void
     */
    public function testIsCurrenciesAvailableIdsValid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, true),
            new CurrencyDefRecStub("USD", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->assertTrue($currencyManager->isCurrenciesAvailable(["RUB", "eur"]));
    }

    /**
     * @return void
     */
    public function testIsCurrenciesAvailableIdsInvalid()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 0, true),
            new CurrencyDefRecStub("EUR", 0, false),
            new CurrencyDefRecStub("USD", 0, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $this->assertFalse($currencyManager->isCurrenciesAvailable(["RUB", "eur"]));
    }

    /**
     * @return void
     */
    public function testCreateCurrencyAmount()
    {
        $curDefManager = new CurrencyDefManagerStub([
            new CurrencyDefRecStub("RUB", 2, true),
        ]);
        $curConvMultiManager = new CurrencyConvMultiplierManagerStub([
        ]);
        $currencyManager = new CurrencyManager(
            $curDefManager,
            $curConvMultiManager
        );
        $amount = $currencyManager->numberToCurrencyAmount("RUB", 120);
        $this->assertEquals("RUB", $amount->getCurId());
        $this->assertEquals(12000, $amount->getDecades());
        $this->assertEquals(2, $amount->getDotPosition());
        $this->assertEquals(120, $amount->toNumber());
    }
}