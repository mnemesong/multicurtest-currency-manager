# multicurtest-currency-manager
Divan.ru test task: amount service


## Description
Manager countains logic to produce and operate amounts of currencies.
Used in some other domain services.

Records aggregation:
- CurrencyDefRec
- CurrencyConvMultiplierRec


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

/**
 * Manager countains logic to produce and operate amounts of currencies.
 * Used in some other domain services.
 */
final class CurrencyManager implements
    \Pantagruel74\MulticurtestBankManagementService\managers\CurrencyManagerInterface,
    \Pantagruel74\MulticurtestPrivateOperationsService\managers\CurrencyManagerInterface,
    \Pantagruel74\MulticurtestAccountAdministrationsService\managers\AvailableCurrencyMangerInterface
{

    /**
     * Request all currencies exists
     * @return array|string[]
     */
    public function getAllCurrenciesExists(): array
    {...}

    /**
     * Converts string to valid currency id.
     * @param string $newCurId
     * @return string
     */
    public function convertNameToNewCurrencyToValid(string $newCurId): string
    {...}

    /**
     * Convert AmountInCurrency to other currency
     * @param AmountInCurrencyVal $amount
     * @param string $targetCurrency
     * @return AmountInCurrencyVal
     */
    public function convertAmountTo(
        $amount,
        string $targetCurrency
    ): AmountInCurrencyVal {...}

    /**
     * Sets new conversion multiplier between currencies.
     * @param string $fromCur
     * @param CurrencyConversionMultiplierVal $conversionMultipliersTo
     * @return void
     */
    public function setConversionMultiplier(
        string $fromCur,
        CurrencyConversionMultiplierVal $conversionMultipliersTo
    ): void {...}

    /**
     * Command to add new currency.
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
     * Command to switching off currency.
     * @param string $curId
     * @return void
     */
    public function switchOffCurrency(string $curId): void
    {...}

    /**
     * Produce a zero value of currency
     * @param string $curId
     * @return AmountInCurrencyVal
     */
    public function getZeroForCurrency(string $curId): AmountInCurrencyVal
    {...}

    /**
     * Check is currencies available by list of ids.
     * @param string[] $curIds
     * @return bool
     */
    public function isCurrenciesAvailable(array $curIds): bool
    {...}

    /**
     * Converts float number to value-object of Currency amount with same amount,
     * as a float number.
     * @param string $curId
     * @param float $val
     * @return AmountInCurrencyVal
     */
    public function numberToCurrencyAmount(
        string $curId,
        float $val
    ): AmountInCurrencyVal {...}
}
```