<?php

namespace Pantagruel74\MulticurtestCurrencyManager;

use Pantagruel74\MulticurtestBankManagementService\values\CurrencyConversionMultiplierVal;
use Pantagruel74\MulticurtestCurrencyManager\managers\CurrencyConvMultiplierMangerInterface;
use Pantagruel74\MulticurtestCurrencyManager\managers\CurrencyDefManagerInterface;
use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyConvMultiplierRecInterface;
use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyDefRecInterface;
use Pantagruel74\MulticurtestCurrencyManager\value\AmountInCurrencyVal;
use Pantagruel74\MulticurtestPrivateOperationsService\values\AmountInCurrencyValInterface;
use Webmozart\Assert\Assert;

final class CurrencyManager implements
    \Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface,
    \Pantagruel74\MulticurtestPrivateOperationsService\managers\CurrencyManagerInterface
{
    private CurrencyDefManagerInterface $currencyDefManager;
    private CurrencyConvMultiplierMangerInterface $currencyConvMultiplierManger;
    private array $currencyDotPositionCache = [];

    /**
     * @param CurrencyDefManagerInterface $currencyDefManager
     * @param CurrencyConvMultiplierMangerInterface $currencyConvMultiplierManger
     */
    public function __construct(
        CurrencyDefManagerInterface $currencyDefManager,
        CurrencyConvMultiplierMangerInterface $currencyConvMultiplierManger
    ) {
        $this->currencyDefManager = $currencyDefManager;
        $this->currencyConvMultiplierManger = $currencyConvMultiplierManger;
    }

    /**
     * @return array|string[]
     */
    public function getAllCurrenciesExists(): array
    {
        return array_map(
            fn(CurrencyDefRecInterface $c) => $c->getCurId(),
            $this->currencyDefManager->getAllAvailable()
        );
    }

    /**
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string
    {
        $newName = strtoupper($newCurId);
        $matches = [];
        preg_match("/^[A-Z]+$/", $newName, $matches);
        Assert::notEmpty($matches);
        return $newName;
    }

    /**
     * @param AmountInCurrencyVal $amount
     * @param string $targetCurrency
     * @return AmountInCurrencyVal
     */
    public function convertAmountTo(
        $amount,
        string $targetCurrency
    ): AmountInCurrencyVal {
        Assert::isAOf($amount, AmountInCurrencyVal::class);
        $targetCurrency = $this->convertNameToNewCurrencyToValid($targetCurrency);
        $multipliers = $this->currencyConvMultiplierManger
            ->getMultipliersBetween($amount->getCurId(), $targetCurrency);
        Assert::notEmpty($multipliers, "Fou currencies "
            . $amount->getCurId() . " and " . $targetCurrency
            . " conversion multipliers are not defined");
        $last = array_reduce(
            $multipliers,
            fn(
                CurrencyConvMultiplierRecInterface $m1,
                CurrencyConvMultiplierRecInterface $m2
            ) => ($m2->getTimestamp() > $m1->getTimestamp()) ? $m2 : $m1,
            $multipliers[0]
        );
        /* @var CurrencyConvMultiplierRecInterface $last */
        $multVal = ($last->getFromCurId() === $amount->getCurId())
            ? $last->getMultiplier()
            : (1 / $last->getMultiplier());
        $unitsInOldCurrency = floatval($amount->getDecades())
            / pow(10, $amount->getDotPosition());
        $unitsInNewCurrency = $unitsInOldCurrency * $multVal;
        $newCurDotPos = $this->getCurrencyDotPosition($targetCurrency);
        $decadesInNewCurrency = intval($unitsInNewCurrency
            * pow(10, $newCurDotPos));
        return new AmountInCurrencyVal(
            $decadesInNewCurrency,
            $newCurDotPos,
            $targetCurrency
        );
    }

    /**
     * @param string $fromCur
     * @param CurrencyConversionMultiplierVal $conversionMultipliersTo
     * @return void
     */
    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void {
        $fromCur = $this->convertNameToNewCurrencyToValid($fromCur);
        $toCur = $this->convertNameToNewCurrencyToValid(
            $conversionMultipliersTo->getCurTo());
        $multi = $conversionMultipliersTo->getMultiplier();
        Assert::notEq($fromCur, $toCur, "Unavailable define convertion "
            . "from currency to it self");
        Assert::notEq($multi, 0, "Zero - is unavailable value"
            . " for convertion multiplier");
        $existsCurrencyIds = $this->getAllCurrenciesExists();
        Assert::inArray($fromCur, $existsCurrencyIds, "Currency "
            . $fromCur . " are not defined");
        Assert::inArray($toCur, $existsCurrencyIds, "Currency "
            . $toCur . " are not defined");
        $newMult = $this->currencyConvMultiplierManger->createNewMultiplier(
            $fromCur,
            $toCur,
            $multi
        );
        $this->currencyConvMultiplierManger
            ->saveNewMany([$newMult]);
    }

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @param int $decimalPosition
     * @return void
     */
    public function addCurrency(
        string $curId,
        array $conversionMultipliersTo,
        int $decimalPosition
    ): void {
        Assert::true($decimalPosition >= 0,
            "Decimal position can't be negative");
        Assert::allIsAOf($conversionMultipliersTo,
            CurrencyConversionMultiplierVal::class);
        $curId = $this->convertNameToNewCurrencyToValid($curId);
        $existsCurrencyIds = $this->getAllCurrenciesExists();
        Assert::false(in_array($curId, $existsCurrencyIds), "Currency "
            . $curId . " already exists");
        $argumentTargetCurrencies = array_map(
            fn(CurrencyConversionMultiplierVal $m) => $m->getCurTo(),
            $conversionMultipliersTo
        );
        Assert::uniqueValues($argumentTargetCurrencies, "Multipliers "
            . "for some currencies are repeat");
        foreach ($existsCurrencyIds as $eci) {
            Assert::inArray($eci, $argumentTargetCurrencies, "Multiplier"
                . " for currency " . $eci . " is required but not given");
        }
        $cur = $this->currencyDefManager->create($curId, $decimalPosition);
        $this->currencyDefManager->save($cur);
        $multiRecs = array_map(
            function (CurrencyConversionMultiplierVal $ccmv)
                use ($existsCurrencyIds, $curId)
            {
                Assert::inArray($ccmv->getCurTo(), $existsCurrencyIds,
                    "Currency " . $ccmv->getCurTo() . " are not exists");
                return $this->currencyConvMultiplierManger->createNewMultiplier(
                    $curId,
                    $ccmv->getCurTo(),
                    $ccmv->getMultiplier()
                );
            },
            $conversionMultipliersTo
        );
        $this->currencyConvMultiplierManger->saveNewMany($multiRecs);
    }

    /**
     * @param string $curId
     * @return void
     */
    public function switchOffCurrency(string $curId): void
    {
        $cur = $this->currencyDefManager
            ->getCurrency($curId)
            ->asUnavailable();
        $this->currencyDefManager->save($cur);
    }

    /**
     * @param string $curId
     * @return AmountInCurrencyVal
     */
    public function getZeroForCurrency(string $curId): AmountInCurrencyVal
    {
        $curId = $this->convertNameToNewCurrencyToValid($curId);
        $dotPos = $this->getCurrencyDotPosition($curId);
        return new AmountInCurrencyVal(0, $dotPos, $curId);
    }

    /**
     * @param $curId
     * @return int
     */
    private function getCurrencyDotPosition($curId): int
    {
        $curId = $this->convertNameToNewCurrencyToValid($curId);
        if(array_key_exists($curId, $this->currencyDotPositionCache)) {
            $val = intval($this->currencyDotPositionCache[$curId]);
        } else {
            $val = $this->currencyDefManager->getCurrency($curId)
                ->getDotPosition();
        }
        Assert::true($val >= 0);
        $this->currencyDotPositionCache[$curId] = $val;
        return $val;
    }
}