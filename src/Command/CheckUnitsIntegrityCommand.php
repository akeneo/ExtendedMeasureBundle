<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Command;

use Pim\Bundle\ExtendedMeasureBundle\Exception\DuplicateUnitException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Check all yaml units definition files
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class CheckUnitsIntegrityCommand extends ContainerAwareCommand
{
    /** @var string[] */
    protected $errors;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pim:measures:check')
            ->setDescription('Checks measures defiition files');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->write($output, "<info>Start measures checks</info>");
        $measuresConfiguration = $this->getContainer()->getParameter('akeneo_measure.measures_config');

        $this->errors = [];

        foreach ($measuresConfiguration['measures_config'] as $family => $familyConfig) {
            $this->validateFamilyUnits($familyConfig['units'], $family);
        }

        foreach ($this->errors as $error) {
            $this->write($output, $error);
        }
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
                if (isset($unitConfig['alternative_symbols'])) {
                    foreach ($unitConfig['alternative_symbols'] as $alternativeUnit) {
                        $familyUnits = $this->checkFamilyUnitUnicity($alternativeUnit, $familyUnits);
                    }
                }
            } catch (DuplicateUnitException $e) {
                $this->errors[] = sprintf('%s -> %s: %s', $familyName, $akeneoUnit, $e->getMessage());
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
     * @param OutputInterface $output
     * @param string          $message
     */
    private function write(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message));
    }
}
