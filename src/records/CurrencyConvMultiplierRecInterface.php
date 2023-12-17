<?php

namespace Pantagruel74\MulticurtestCurrencyManager\records;

interface CurrencyConvMultiplierRecInterface
{
    public function getFromCurId(): string;
    public function getToCurId(): string;
    public function getTimestamp(): int;
    public function getMultiplier(): float;
}