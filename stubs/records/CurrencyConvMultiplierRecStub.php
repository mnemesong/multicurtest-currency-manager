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
     * @param float $multiplier
     * @param int|null $timestamp
     */
    public function __construct(
        string $fromCurId,
        string $toCurId,
        float $multiplier,
        ?int $timestamp = null
    ) {
        $this->fromCurId = $fromCurId;
        $this->toCurId = $toCurId;
        $this->timestamp = is_null($timestamp)
            ? (new \DateTime("now"))->getTimestamp()
            : $timestamp;
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