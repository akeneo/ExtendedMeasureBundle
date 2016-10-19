<?php

namespace Pim\Bundle\ExtendedMeasureBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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

        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            if (is_dir($configDirectory = dirname($reflection->getFileName()).'/Resources/config/measures')) {
                $bundleConfig = $this->parseBundleMeasures($configDirectory);
                $measuresConfig = array_replace_recursive($measuresConfig, $bundleConfig);
            }
        }

        $coreConfig = $container->getParameter('akeneo_measure.measures_config');
        $measuresConfig = array_replace_recursive($coreConfig, $measuresConfig);
        $container->setParameter('akeneo_measure.measures_config', $measuresConfig);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function parseBundleMeasures($configDirectory)
    {
        $measuresFinder = new Finder();
        $measuresFinder->files()->in($configDirectory)->name(('*.yml'));
        $measuresConfig = [];

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

        return $measuresConfig;
    }
}
