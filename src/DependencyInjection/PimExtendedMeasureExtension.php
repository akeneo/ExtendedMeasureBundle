<?php

namespace Pim\Bundle\ExtendedMeasureBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class PimExtendedMeasureExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $measuresConfig = [];

        $configDirectory = __DIR__ . '/../Resources/config/measures';
        $measuresFinder = new Finder();
        $measuresFinder->files()->in($configDirectory)->name(('*.yml'));

        foreach ($measuresFinder as $file) {
            if (empty($measuresConfig)) {
                $measuresConfig = Yaml::parse($file->getContents());
            } else {
                $entities = Yaml::parse($file->getContents());
                foreach ($entities['measures_config'] as $family => $familyConfig) {
                    if (isset($measuresConfig['measures_config'][$family])) {
                        $measuresConfig['measures_config'][$family]['units'] =
                            array_merge(
                                $measuresConfig['measures_config'][$family]['units'],
                                $familyConfig['units']
                            );
                    } else {
                        $measuresConfig['measures_config'][$family] = $familyConfig;
                    }
                }
            }
        }
        $preset = $container->getParameter('akeneo_measure.measures_config');
        $container->setParameter('akeneo_measure.measures_config', array_replace_recursive($preset, $measuresConfig));
    }
}
