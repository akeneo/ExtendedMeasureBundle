<?php

namespace spec\Pim\Bundle\ExtendedMeasureBundle\Validator;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Resolver\MeasureResolverInterface;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class ConfigurationValidatorSpec extends ObjectBehavior
{
    public function it_validates_correct_configuration()
    {
        $config = require (__DIR__ . '/../Resources/measures/merged_configuration.php');

        $this->validate($config)->shouldReturn([]);
    }

    public function it_does_not_validate_bad_configuration()
    {
        $config = require (__DIR__ . '/../Resources/measures/bad_configuration.php');

        $this->validate($config)->shouldReturn([
            'Weight : Unit already exists: kg'
        ]);
    }
}
