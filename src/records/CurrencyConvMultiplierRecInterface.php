<?php

namespace Pantagruel74\MulticurtestCurrencyManager\records;

/**
 * Contract of CurrencyConvMultiplierRecord entity.
 */
interface CurrencyConvMultiplierRecInterface
{
    /**
     * Get FROM currencyId of object.
     * @return string
     */
    public function getFromCurId(): string;

    /**
     * Get To currencyId of object.
     * @return string
     */
    public function getToCurId(): string;

    /**
     * Get timestamp of created.
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * Get multiplier value.
     * @return float
     */
    public function getMultiplier(): float;
}