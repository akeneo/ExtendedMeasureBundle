<?php

namespace Pim\Bundle\ExtendedMeasureBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;

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
        $configDirectory = __DIR__ . '/../Resources/config';
        $loader = new Loader\YamlFileLoader($container, new FileLocator($configDirectory));
    }
}
