<?php
namespace Pim\Bundle\ExtendedMeasureBundle\Resolver;

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
     */
    public function resolvePimMeasure($unit);
}
