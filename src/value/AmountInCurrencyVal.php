<?php

namespace Pantagruel74\MulticurtestCurrencyManager\value;

use Pantagruel74\MulticurtestPrivateOperationsService\values\AmountInCurrencyValInterface;
use Webmozart\Assert\Assert;

class AmountInCurrencyVal implements
    \Pantagruel74\MulticurtestBankManagementService\values\AmountInCurrencyValInterface,
    \Pantagruel74\MulticurtestPrivateOperationsService\values\AmountInCurrencyValInterface
{
    protected int $decades;
    protected int $dotPosition;
    protected string $curId;

    /**
     * @param int $decades
     * @param int $dotPosition
     * @param string $curId
     */
    public function __construct(int $decades, int $dotPosition, string $curId)
    {
        $this->decades = $decades;
        $this->dotPosition = $dotPosition;
        $this->curId = $curId;
    }

    /**
     * @param AmountInCurrencyValInterface $anotherAmount
     * @return $this
     */
    public function minus(AmountInCurrencyValInterface $anotherAmount): self
    {
        Assert::isAOf($anotherAmount, self::class);
        Assert::eq($anotherAmount->getCurId(), $this->curId);
        /* @var self $anotherAmount */
        return new self(
            $this->decades - $anotherAmount->decades,
            $this->dotPosition,
            $this->curId
        );
    }

    /**
     * @return string
     */
    public function getCurId(): string
    {
        return $this->curId;
    }

    /**
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->decades > 0;
    }

    /**
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->decades === 0;
    }

    /**
     * @return $this
     */
    public function reverse(): self
    {
        return new self(
            0 - $this->decades,
            $this->dotPosition,
            $this->curId
        );
    }

    /**
     * @param AmountInCurrencyValInterface $anotherAmount
     * @return $this
     */
    public function plus(AmountInCurrencyValInterface $anotherAmount): self
    {
        Assert::isAOf($anotherAmount, self::class);
        Assert::eq($anotherAmount->getCurId(), $this->curId);
        /* @var self $anotherAmount */
        return new self(
            $this->decades + $anotherAmount->decades,
            $this->dotPosition,
            $this->curId
        );
    }

    /**
     * @return int
     */
    public function getDecades(): int
    {
        return $this->decades;
    }

    /**
     * @return int
     */
    public function getDotPosition(): int
    {
        return $this->dotPosition;
    }

}