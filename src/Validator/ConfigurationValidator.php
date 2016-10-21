<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Validator;

use Pim\Bundle\ExtendedMeasureBundle\Exception\DuplicateUnitException;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $this->errors = [];

        foreach ($config['measures_config'] as $family => $familyConfig) {
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
    public function validateFamilyUnits(array $unitsConfig, $familyName)
    {
        $options = new OptionsResolver();
        $this->configureOptions($options);
        $familyUnits = [];
        foreach ($unitsConfig as $akeneoUnit => $unitConfig) {
            try {
                $unitConfig = $options->resolve($unitConfig);
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

    /**
     * @param OptionsResolver $options
     */
    private function configureOptions(OptionsResolver $options)
    {
        $options->setRequired('convert');
        $options->setAllowedTypes('convert', 'array');
        $options->setRequired('symbol');
        $options->setAllowedTypes('symbol', 'string');
        $options->setDefined('unece_code');
        $options->setAllowedTypes('unece_code', 'string');
        $options->setDefined('name');
        $options->setAllowedTypes('name', 'string');
        $options->setDefined('alternative_units');
        $options->setAllowedTypes('alternative_units', 'array');
    }
}
