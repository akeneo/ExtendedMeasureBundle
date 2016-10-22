<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Resolver;

use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;

/**
 * Resolve a measure to a a PIM unit by its symbol or unit.
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasureResolver implements MeasureResolverInterface
{
    /**
     * Akeneo PIM merged measures configurations
     *
     * @var array
     */
    private $pimConfig;

    /**
     * @var array
     */
    private $unitNames;

    /**
     * @var array
     */
    private $unitFamilies;

    /**
     * Unresolvable measures because of a unit in more than one family
     *
     * @var array
     */
    private $unresolvableMeasures;

    /**
     * @param array $pimConfig
     */
    public function __construct(array $pimConfig)
    {
        $this->pimConfig = $pimConfig;
        $this->buildResolvableUnits($pimConfig['measures_config']);
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePimMeasure($unit)
    {
        if (array_key_exists($unit, $this->unresolvableMeasures)) {
            $message = sprintf('Unable to resolve the unit "%s" in', $unit);
            foreach ($this->unresolvableMeasures[$unit] as $unresolvables) {
                $message .= vsprintf(' [family: %s, measure: %s]', $unresolvables);
            }
            throw new UnresolvableUnitException($message);
        }
        if (!array_key_exists($unit, $this->unitNames)) {
            throw new UnknownUnitException($unit);
        }

        return [
            'family' => $this->unitFamilies[$unit],
            'unit'   => $this->unitNames[$unit],
        ];
    }

    /**
     * Build a reverse config to find a measure by unit
     *
     * @param array $measuresConfig
     */
    private function buildResolvableUnits($measuresConfig)
    {
        $this->unitNames = [];
        $this->unresolvableMeasures = [];
        foreach ($measuresConfig as $pimFamily => $units) {
            foreach ($units['units'] as $pimUnit => $unitConfig) {
                $this->parseUnit($unitConfig, $pimUnit, $pimFamily);
            }
        }
    }

    /**
     * Parse one measure definition:
     *      CUBIC_MILLIMETER:
     *          unece_code: 'MMQ'
     *          convert: [{'mul': 0.000000001}]
     *          symbol: 'mm³'
     *          name: 'cubic millimeter'
     *          alternative_units: ['foo', 'bar']
     *
     * @param array  $unitConfig
     * @param string $pimUnitName
     * @param string $pimFamily
     */
    private function parseUnit(array $unitConfig, $pimUnitName, $pimFamily)
    {
        $this->resolveUnit($unitConfig['symbol'], $pimUnitName, $pimFamily);
        if (isset($unitConfig['unece_code'])) {
            $this->resolveUnit($unitConfig['unece_code'], $pimUnitName, $pimFamily);
        }
        if (isset($unitConfig['alternative_units'])) {
            foreach ($unitConfig['alternative_units'] as $alternativeUnit) {
                // process UTF8 entities. Using json_decode is a bit hacky, but it is the simplest way
                if (strpos($alternativeUnit, '\u') === 0) {
                    $alternativeUnit = json_decode('"' . $alternativeUnit . '"');
                }
                $this->resolveUnit($alternativeUnit, $pimUnitName, $pimFamily);
            }
        }
    }

    /**
     * Identify unresolvable units that are in more than one family.
     *
     * @param string $unit
     * @param string $pimUnitName
     * @param string $pimFamily
     */
    private function resolveUnit($unit, $pimUnitName, $pimFamily)
    {
        if (isset($this->unresolvableMeasures[$unit])) {
            $this->unresolvableMeasures[$unit][] = [$pimFamily, $pimUnitName];
        } elseif (isset($this->unitFamilies[$unit])) {
            $this->unresolvableMeasures[$unit] = [
                [$this->unitFamilies[$unit], $this->unitNames[$unit]],
                [$pimFamily, $pimUnitName],
            ];
            unset($this->unitNames[$unit]);
            unset($this->unitFamilies[$unit]);
        } else {
            $this->unitNames[$unit] = $pimUnitName;
            $this->unitFamilies[$unit] = $pimFamily;
        }
    }
}
