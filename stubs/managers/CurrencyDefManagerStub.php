<?php

namespace Pantagruel74\MulticurtestCurrencyManagerStubs\managers;

use Pantagruel74\MulticurtestCurrencyManager\managers\CurrencyDefManagerInterface;
use Pantagruel74\MulticurtestCurrencyManager\records\CurrencyDefRecInterface;
use Pantagruel74\MulticurtestCurrencyManagerStubs\records\CurrencyDefRecStub;
use Webmozart\Assert\Assert;

class CurrencyDefManagerStub implements CurrencyDefManagerInterface
{
    private array $curs = [];

    /**
     * @param CurrencyDefRecStub[] $curs
     */
    public function __construct(array $curs)
    {
        Assert::allIsAOf($curs, CurrencyDefRecStub::class);
        $this->curs = $curs;
    }

    /**
     * @param string $curId
     * @return CurrencyDefRecInterface
     */
    public function getCurrency(string $curId): CurrencyDefRecInterface
    {
        $cur = array_values(array_filter(
            $this->curs,
            fn(CurrencyDefRecStub $c) => ($c->getCurId() === $curId)
        ));
        Assert::notEmpty($cur);
        return $cur[0];
    }

    /**
     * @return CurrencyDefRecInterface[]
     */
    public function getAllAvailable(): array
    {
        return array_values(array_filter(
            $this->curs,
            fn(CurrencyDefRecStub $c) => ($c->isAvailable())
        ));
    }

    /**
     * @param string $curId
     * @param int $dotPosition
     * @return CurrencyDefRecInterface
     */
    public function create(
        string $curId,
        int $dotPosition
    ): CurrencyDefRecInterface {
        Assert::true($dotPosition > 0);
        return new CurrencyDefRecStub($curId, $dotPosition, true);
    }

    /**
     * @param CurrencyDefRecInterface $cur
     * @return void
     */
    public function save(CurrencyDefRecInterface $cur): void
    {
        $existsCurIds = array_map(
            fn(CurrencyDefRecStub $c) => $c->getCurId(),
            $this->curs
        );
        $i = array_search($cur->getCurId(), $existsCurIds);
        if($i === false) {
            $this->curs[] = $cur;
        } else {
            $this->curs[$i] = $cur;
        }
    }
}