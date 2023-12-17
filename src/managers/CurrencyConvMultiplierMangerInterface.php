<?php

namespace Pantagruel74\MulticurtestCurrencyManager\managers;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyConvMultiplierRecInterface;

interface CurrencyConvMultiplierMangerInterface
{
    /**
     * @param string $cur1
     * @param string $cur2
     * @return CurrencyConvMultiplierRecInterface[]
     */
    public function getMultipliersBetween(string $cur1, string $cur2): array;

    /**
     * @param string $curFrom
     * @param string $curTo
     * @param float $multi
     * @return CurrencyConvMultiplierRecInterface
     */
    public function createNewMultiplier(
        string $curFrom,
        string $curTo,
        float $multi
    ): CurrencyConvMultiplierRecInterface;

    /**
     * @param CurrencyConvMultiplierRecInterface[] $curMultipliers
     * @return void
     */
    public function saveNewMany(array $curMultipliers): void;
}