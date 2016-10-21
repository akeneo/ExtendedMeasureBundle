<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Resolver;

use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;

/**
 * Resolve a measure to a a PIM unit
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
interface MeasureResolverInterface
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
    public function resolvePimMeasure($unit);
}
