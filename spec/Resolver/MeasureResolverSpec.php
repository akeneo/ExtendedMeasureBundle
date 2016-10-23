<?php

namespace spec\Pim\Bundle\ExtendedMeasureBundle\Resolver;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnknownUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Exception\UnresolvableUnitException;
use Pim\Bundle\ExtendedMeasureBundle\Resolver\MeasureResolverInterface;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasureResolverSpec extends ObjectBehavior
{
    public function let()
    {
        $config = require (__DIR__ . '/../Resources/measures/merged_configuration.php');
        $this->beConstructedWith($config);
    }

    public function it_is_initializable()
    {
        $this->shouldImplement(MeasureResolverInterface::class);
    }

    public function it_returns_measure_from_a_unit()
    {
        $this
            ->resolvePimMeasure('kg')
            ->shouldReturn(
                [
                    'family' => 'Weight',
                    'unit'   => 'KILOGRAM'
                ]
            );
        $this
            ->resolvePimMeasure('kilo')
            ->shouldReturn(
                [
                    'family' => 'Weight',
                    'unit'   => 'KILOGRAM'
                ]
            );
        $this
            ->resolvePimMeasure('mt')
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
            ->resolvePimMeasure('KGM')
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
            ->during('resolvePimMeasure', ['parsec']);
    }

    public function it_throws_an_exception_for_unresolvable_unit()
    {
        $message = 'Unable to resolve the unit "m" in [family: Weight, measure: BADMETER] [family: Length, measure: METER]';
        $this
            ->shouldThrow(
                new UnresolvableUnitException($message)
            )
            ->during('resolvePimMeasure', ['m']);
    }
}
