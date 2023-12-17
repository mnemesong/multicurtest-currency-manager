<?php

namespace Pantagruel74\MulticurtestCurrencyManagerStubs\records;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyConvMultiplierRecInterface;

class CurrencyConvMultiplierRecStub implements CurrencyConvMultiplierRecInterface
{
    private string $fromCurId;
    private string $toCurId;
    private int $timestamp;
    private float $multiplier;

    /**
     * @param string $fromCurId
     * @param string $toCurId
     * @param int $timestamp
     * @param float $multiplier
     */
    public function __construct(
        string $fromCurId,
        string $toCurId,
        int $timestamp,
        float $multiplier
    ) {
        $this->fromCurId = $fromCurId;
        $this->toCurId = $toCurId;
        $this->timestamp = $timestamp;
        $this->multiplier = $multiplier;
    }

    /**
     * @return string
     */
    public function getFromCurId(): string
    {
        return $this->fromCurId;
    }

    /**
     * @return string
     */
    public function getToCurId(): string
    {
        return $this->toCurId;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return float
     */
    public function getMultiplier(): float
    {
        return $this->multiplier;
    }
}