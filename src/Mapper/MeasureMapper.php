<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Mapper;

use Pim\Bundle\ExtendedMeasureBundle\Exception\DuplicateUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;

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
    private $unresolvableUnits;

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
        if (array_key_exists($unit, $this->unresolvableUnits)) {
            throw new DuplicateUnitException();
        }
        if (!array_key_exists($unit, $this->unitNames)) {
            throw new UnknownUnitException();
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
        $this->unresolvableUnits = [];
        foreach ($measuresConfig as $unitFamily => $units) {
            foreach ($units['units'] as $pimUnit => $unitConfig) {
                $this->parseUnit($unitConfig, $pimUnit, $unitFamily);
            }
        }
    }

    /**
     * @param array  $unitConfig
     * @param string $pimUnitName
     * @param string $unitFamily
     */
    private function parseUnit(array $unitConfig, $pimUnitName, $unitFamily)
    {
        $this->resolveUnit($unitConfig['symbol'], $pimUnitName, $unitFamily);
        if (isset($unitConfig['unece_code'])) {
            $this->resolveUnit($unitConfig['unece_code'], $pimUnitName, $unitFamily);
        }
        if (isset($unitConfig['alternative_units'])) {
            foreach ($unitConfig['alternative_units'] as $alternativeUnit) {
                $this->resolveUnit($alternativeUnit, $pimUnitName, $unitFamily);
            }
        }
    }

    /**
     * @param string $unit
     * @param string $pimUnitName
     * @param string $unitFamily
     */
    private function resolveUnit($unit, $pimUnitName, $unitFamily)
    {
        if (isset($this->unresolvableUnits[$unit])) {
            return;
        }
        if (!isset($this->unitNames[$unit])) {
            $this->unitNames[$unit] = $pimUnitName;
            $this->unitFamilies[$unit] = $unitFamily;
        } else {
            unset($this->unitNames[$unit]);
            unset($this->unitFamilies[$unit]);
            $this->unresolvableUnits[$unit] = 1;
        }
    }
}
