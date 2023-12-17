<?php

namespace Pantagruel74\MulticurtestCurrencyManagerStubs\managers;

use Pantagruel74\MulticurtestCurrencyManager\managers\CurrencyConvMultiplierMangerInterface;
use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyConvMultiplierRecInterface;
use Pantagruel74\MulticurtestCurrencyManagerStubs\records\CurrencyConvMultiplierRecStub;
use Webmozart\Assert\Assert;

class CurrencyConvMultiplierManagerStub implements
    CurrencyConvMultiplierMangerInterface
{
    private array $multsLog = [];

    /**
     * @param array $multsLog
     */
    public function __construct(array $multsLog)
    {
        Assert::allIsAOf($multsLog, CurrencyConvMultiplierRecStub::class);
        $this->multsLog = $multsLog;
    }

    /**
     * @param string $cur1
     * @param string $cur2
     * @return CurrencyConvMultiplierRecInterface[]
     */
    public function getMultipliersBetween(string $cur1, string $cur2): array
    {
        return array_filter(
            $this->multsLog,
            fn(CurrencyConvMultiplierRecStub $ccmrs)
                => (($ccmrs->getFromCurId() === $cur1)
                    && ($ccmrs->getToCurId() === $cur2))
                || (($ccmrs->getFromCurId() === $cur2)
                    && ($ccmrs->getToCurId() === $cur1))
        );
    }

    /**
     * @param string $curFrom
     * @param string $curTo
     * @param float $multi
     * @return CurrencyConvMultiplierRecInterface
     */
    public function createNewMultiplier(
        string $curFrom,
        string $curTo,
        float $multi
    ): CurrencyConvMultiplierRecInterface {
        return new CurrencyConvMultiplierRecStub(
            $curFrom,
            $curTo,
            (new \DateTime("now"))->getTimestamp(),
            $multi
        );
    }

    /**
     * @param CurrencyConvMultiplierRecStub[] $curMultipliers
     * @return void
     */
    public function saveNewMany(array $curMultipliers): void
    {
        Assert::allIsAOf($curMultipliers,
            CurrencyConvMultiplierRecStub::class);
        $this->multsLog = array_merge(
            $this->multsLog,
            $curMultipliers
        );
    }
}