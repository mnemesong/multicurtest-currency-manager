<?php

namespace Pantagruel74\MulticurtestCurrencyManager\records;

interface CurrencyDefRecInterface
{
    public function getCurId(): string;
    public function getDotPosition(): int;
    public function isAvailable(): bool;
    public function asUnavailable(): self;
}