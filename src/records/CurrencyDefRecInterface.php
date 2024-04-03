<?php

namespace Pantagruel74\MulticurtestCurrencyManager\records;

/**
 * Contract of CurrencyDefRecord entity.
 */
interface CurrencyDefRecInterface
{
    /**
     * Get currency id.
     * @return string
     */
    public function getCurId(): string;

    /**
     * Get dot floating position of value.
     * @return int
     */
    public function getDotPosition(): int;

    /**
     * Checks is currency now available in the bank?
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Marks currency as unavailable in the bank.
     * @return $this
     */
    public function asUnavailable(): self;
}