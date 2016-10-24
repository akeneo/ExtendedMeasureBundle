<?php

namespace spec\Pim\Bundle\ExtendedMeasureBundle\Repository;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Repository\MeasureRepositoryInterface;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasureRepositorySpec extends ObjectBehavior
{
    public function let()
    {
        $config = require (__DIR__ . '/../Resources/measures/merged_configuration.php');
        $this->beConstructedWith($config);
    }

    public function it_is_initializable()
    {
        $this->shouldImplement(MeasureRepositoryInterface::class);
    }

    public function it_returns_measure_from_a_unit()
    {
        $this
            ->findByUnit('kg')
            ->shouldReturn(
                [
                    'family' => 'Weight',
                    'unit'   => 'KILOGRAM'
                ]
            );
        $this
            ->findByUnit('kilo')
            ->shouldReturn(
                [
                    'family' => 'Weight',
                    'unit'   => 'KILOGRAM'
                ]
            );
        $this
            ->findByUnit('mt')
            ->shouldReturn(
                [
                    'family' => 'Length',
                    'unit'   => 'METER'
                ]
            );
    }

    public function it_returns_measure_from_unece_code()
    {
        $this
            ->findByUnit('KGM')
            ->shouldReturn(
                [
                    'family' => 'Weight',
                    'unit'   => 'KILOGRAM'
                ]
            );
    }

    public function it_throws_an_exception_for_unknown_unit()
    {
        $this
            ->shouldThrow(
                new UnknownUnitException('parsec')
            )
            ->during('findByUnit', ['parsec']);
    }

    public function it_throws_an_exception_for_unresolvable_unit()
    {
        $message = 'Unable to resolve the unit "m" in [family: Weight, measure: BADMETER] [family: Length, measure: METER]';
        $this
            ->shouldThrow(
                new UnresolvableUnitException($message)
            )
            ->during('findByUnit', ['m']);
    }
}
