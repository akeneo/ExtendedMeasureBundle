<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Command;

use Pim\Bundle\ExtendedMeasureBundle\Validator\ConfigurationValidator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Check all yaml measures definition files
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class CheckUnitsIntegrityCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

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
        $this->output = $output;
        $this->write("<info>Start measures checks</info>");
        $measuresConfiguration = $this->getContainer()->getParameter('akeneo_measure.measures_config');
        $validator = new ConfigurationValidator();
        $errors = $validator->validate($measuresConfiguration);

        foreach ($errors as $error) {
            $this->write($error);
        }
    }

    /**
     * @param string $message
     */
    private function write($message)
    {
        $this->output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message));
    }
}
