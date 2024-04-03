<?php

namespace Pantagruel74\MulticurtestCurrencyManagerStubs\records;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyDefRecInterface;
use Webmozart\Assert\Assert;

/**
 * Stub of CurrencyDefRec for tests.
 */
class CurrencyDefRecStub implements CurrencyDefRecInterface
{
    private string $curId;
    private int $dotPosition;
    private bool $available;

    /**
     * @param string $curId
     * @param int $dotPosition
     * @param bool $available
     */
    public function __construct(string $curId, int $dotPosition, bool $available)
    {
        $this->curId = $curId;
        $this->dotPosition = $dotPosition;
        $this->available = $available;
    }

    /**
     * @return string
     */
    public function getCurId(): string
    {
        return $this->curId;
    }

    /**
     * @return int
     */
    public function getDotPosition(): int
    {
        return $this->dotPosition;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @return CurrencyDefRecInterface
     */
    public function asUnavailable(): CurrencyDefRecInterface
    {
        $c = clone $this;
        $c->available = false;
        return $c;
    }
}