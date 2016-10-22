<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Validator;

use Pim\Bundle\ExtendedMeasureBundle\DependencyInjection\MeasuresConfiguration;
use Pim\Bundle\ExtendedMeasureBundle\Exception\DuplicateUnitException;
use Symfony\Component\Config\Definition\Processor;

/**
 * Validate the measures configuration
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class ConfigurationValidator
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @param array $config
     *
     * @return array
     *
     * @throws DuplicateUnitException
     */
    public function validate($config)
    {
        $processor = new Processor();
        $configTree = new MeasuresConfiguration();

        $config = $processor->processConfiguration($configTree, $config);

        $this->errors = [];

        foreach ($config as $family => $familyConfig) {
            $this->validateFamilyUnits($familyConfig['units'], $family);
        }

        return $this->errors;
    }

    /**
     * @param array  $unitsConfig
     * @param string $familyName
     *
     * @throws DuplicateUnitException
     */
    protected function validateFamilyUnits(array $unitsConfig, $familyName)
    {
        $familyUnits = [];
        foreach ($unitsConfig as $akeneoUnit => $unitConfig) {
            try {
                $familyUnits = $this->checkFamilyUnitUnicity($unitConfig['symbol'], $familyUnits);
                if (isset($unitConfig['unece_code'])) {
                    $familyUnits = $this->checkFamilyUnitUnicity($unitConfig['unece_code'], $familyUnits);
                }
                if (isset($unitConfig['alternative_units'])) {
                    foreach ($unitConfig['alternative_units'] as $alternativeUnit) {
                        $familyUnits = $this->checkFamilyUnitUnicity($alternativeUnit, $familyUnits);
                    }
                }
            } catch (DuplicateUnitException $e) {
                $this->errors[] = sprintf('%s : %s', $familyName, $e->getMessage());
            }
        }
    }

    /**
     * @param string   $unit
     * @param string[] $existingUnits
     *
     * @return string[]
     */
    private function checkFamilyUnitUnicity($unit, $existingUnits)
    {
        if (in_array($unit, $existingUnits)) {
            throw new DuplicateUnitException('Unit already exists: ' . $unit);
        }
        $existingUnits[] = $unit;

        return $existingUnits;
    }
}
