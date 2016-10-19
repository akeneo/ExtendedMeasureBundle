<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Mapper;

use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;

/**
 * Map a measure to a a PIM unit
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasureMapper
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $unitNames;

    /**
     * @var array
     */
    private $unitFamilies;

    /**
     * @var array
     */
    private $unresolvableMeasures;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->buildResolvableUnits($config['measures_config']);
    }

    /**
     * @param string $unit
     *
     * @return array
     */
    public function getPimUnit($unit)
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
                $this->resolveUnit($alternativeUnit, $pimUnitName, $pimFamily);
            }
        }
    }

    /**
     * @param string $unit
     * @param string $pimUnitName
     * @param string $pimFamily
     */
    private function resolveUnit($unit, $pimUnitName, $pimFamily)
    {
        if (isset($this->unresolvableMeasures[$unit])) {
            $this->unresolvableMeasures[$unit][] = [$pimFamily, $pimUnitName];
        } elseif (isset($this->unitNames[$unit])) {
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
