<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Repository;

/**
 * Measures repository
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
interface MeasureRepositoryInterface
{
    /**
     * Retrieve a PIM unit from a unitname (METER, KILOGRAM, ...).
     *
     * @param string      $unit
     * @param string|null $family to restrict the search in one family
     *
     * @return array
     */
    public function findByUnit($unit, $family = null);

    /**
     * Retrieve a PIM unit from a symbol (Hz, Km, ...)
     *
     * @param string      $symbol
     * @param string|null $family to restrict the search in one family
     *
     * @return array
     */
    public function findBySymbol($symbol, $family = null);
}
