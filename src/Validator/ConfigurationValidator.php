<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Validator;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class ConfigurationValidator
{
    public function loadDefinitions($configDirectory)
    {
        $measuresFinder = new Finder();
        $measuresFinder->files()->in($configDirectory)->name(('*.yml'));

        foreach ($measuresFinder as $measureFile) {
            $definitions = Yaml::parse($measureFile->getContents());
            dump($definitions);
        }
    }
}
