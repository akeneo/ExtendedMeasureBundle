<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Repository;

use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;

/**
 * Resolve a measure to a a PIM unit
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
interface MeasureRepositoryInterface
{
    /**
     * Retrieve a PIM measure from a unit (Hz, Km, ...)
     *
     * @param string $unit
     *
     * @return array
     *
     * @throws UnresolvableUnitException
     * @throws UnknownUnitException
     */
    public function findByUnit($unit);
}
