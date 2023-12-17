# multicurtest-currency-manager
Divan.ru test task: amount service


## Description
Manager countains logic to produce and operate amounts of currencies.
Used in some other domain services.


## Source structure
- managers
  - CurrencyConvMultiplierManagerInterface
  - CurrencyDefManagerInterface
- records
  - CurrencyConvMultiplierRecInterface
  - CurrencyDefRecInterface
- value
  - AmountinCurrencyVal
CurrencyManager


## API
```php
<?php
namespace Pantagruel74\MulticurtestCurrencyManager;

final class CurrencyManager implements
    \Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface,
    \Pantagruel74\MulticurtestPrivateOperationsService\managers\CurrencyManagerInterface
{

    /**
     * @return array|string[]
     */
    public function getAllCurrenciesExists(): array
    {...}

    /**
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string
    {...}

    /**
     * @param AmountInCurrencyVal $amount
     * @param string $targetCurrency
     * @return AmountInCurrencyVal
     */
    public function convertAmountTo(
        $amount,
        string $targetCurrency
    ): AmountInCurrencyVal {...}

    /**
     * @param string $fromCur
     * @param CurrencyConversionMultiplierVal $conversionMultipliersTo
     * @return void
     */
    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void {...}

    /**
     * @param string $curId
     * @param CurrencyConversionMultiplierVal[] $conversionMultipliersTo
     * @param int $decimalPosition
     * @return void
     */
    public function addCurrency(
        string $curId,
        array $conversionMultipliersTo,
        int $decimalPosition
    ): void {...}

    /**
     * @param string $curId
     * @return void
     */
    public function switchOffCurrency(string $curId): void {...}

    /**
     * @param string $curId
     * @return AmountInCurrencyVal
     */
    public function getZeroForCurrency(string $curId): AmountInCurrencyVal {...}
}
```