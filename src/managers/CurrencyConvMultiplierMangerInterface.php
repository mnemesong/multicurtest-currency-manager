<?php

namespace Pantagruel74\MulticurtestCurrencyManager\managers;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyConvMultiplierRecInterface;

/**
 * Contract of singleton that manage CurrencyConvMultiplier values.
 */
interface CurrencyConvMultiplierMangerInterface
{
    /**
     * Request multipliers between two values from history.
     * @param string $cur1
     * @param string $cur2
     * @return CurrencyConvMultiplierRecInterface[]
     */
    public function getMultipliersBetween(string $cur1, string $cur2): array;

    /**
     * Create new multiplier of conversion between two currencies.
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
     * Save new many currency multipliers.
     * @param CurrencyConvMultiplierRecInterface[] $curMultipliers
     * @return void
     */
    public function saveNewMany(array $curMultipliers): void;
}