<?php

namespace Pantagruel74\MulticurtestCurrencyManager\managers;

use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyDefRecInterface;

/**
 * Contract of singleton, that manage currencies in the bank.
 */
interface CurrencyDefManagerInterface
{
    /**
     * Get currency entity by id.
     * @param string $curId
     * @return CurrencyDefRecInterface
     */
    public function getCurrency(string $curId): CurrencyDefRecInterface;

    /**
     * Get all current moment available currency entities.
     * @return CurrencyDefRecInterface[]
     */
    public function getAllAvailable(): array;

    /**
     * Request to produce new currency entity.
     * @param string $curId
     * @param int $dotPosition
     * @return CurrencyDefRecInterface
     */
    public function create(
        string $curId,
        int $dotPosition
    ): CurrencyDefRecInterface;

    /**
     * Save currency entity.
     * @param CurrencyDefRecInterface $cur
     * @return void
     */
    public function save(CurrencyDefRecInterface $cur): void;
}