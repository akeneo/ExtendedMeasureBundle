<?php

namespace spec\Pim\Bundle\ExtendedMeasureBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class MeasuresCompilerPassSpec extends ObjectBehavior
{
    public function let()
    {
        $configDirectory = __DIR__ . '/../Resources/measures';
        $this->beConstructedWith($configDirectory);
    }

    public function it_is_initializable()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    public function it_processes_configuration(ContainerBuilder $container)
    {
        $container->getParameter('akeneo_measure.measures_config')
            ->willReturn([]);

        $expectedConfig = [
            'measures_config' => [
                'SpecFamily' => [
                    'standard' => 'SPEC_STANDARD',
                    'units'    => [
                        'SPEC_STANDARD' => [
                            'convert'           => [['mul' => 1]],
                            'symbol'            => 'spec',
                            'name'              => 'standard for the specs',
                            'unece_code'        => 'fooCode',
                            'alternative_units' => []
                        ],
                        'KILO_STANDARD' => [
                            'convert'           => [['mul' => 1000]],
                            'symbol'            => 'kilospec',
                            'name'              => 'kilo measure of the standard',
                            'unece_code'        => 'barCode',
                            'alternative_units' => ['kylo']
                        ]
                    ]
                ],
                'Weight'     => [
                    'standard' => 'g',
                    'units'    => [
                        'KILOGRAM'   => [
                            'convert'           => [['mul' => 1000]],
                            'symbol'            => 'kg',
                            'name'              => 'kilogram',
                            'unece_code'        => 'KGM',
                            'alternative_units' => ['kilo', 'kilogramme']
                        ],
                        'WRONGMETER' => [
                            'symbol'            => 'm',
                            'convert'           => [['mul' => 666]],
                            'alternative_units' => []
                        ]
                    ]
                ],
                'Length'     => [
                    'standard' => 'METER',
                    'units'    => [
                        'METER' => [
                            'symbol'            => 'm',
                            'convert'           => [['mul' => 1]],
                            'alternative_units' => []
                        ]
                    ]
                ]
            ]
        ];

        $container->setParameter('akeneo_measure.measures_config', $expectedConfig)
            ->shouldBeCalled();

        $this->process($container);
    }
}
