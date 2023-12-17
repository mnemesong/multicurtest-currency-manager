<?php

namespace Pantagruel74\MulticurtestCurrencyManager\managers;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyDefRecInterface;

interface CurrencyDefManagerInterface
{
    /**
     * @param string $curId
     * @return CurrencyDefRecInterface
     */
    public function getCurrency(string $curId): CurrencyDefRecInterface;

    /**
     * @return CurrencyDefRecInterface[]
     */
    public function getAllAvailable(): array;

    /**
     * @param string $curId
     * @param int $dotPosition
     * @return CurrencyDefRecInterface
     */
    public function create(
        string $curId,
        int $dotPosition
    ): CurrencyDefRecInterface;

    /**
     * @param CurrencyDefRecInterface $cur
     * @return void
     */
    public function save(CurrencyDefRecInterface $cur): void;
}