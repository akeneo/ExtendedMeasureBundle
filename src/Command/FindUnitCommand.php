<?php

namespace Pim\Bundle\ExtendedMeasureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Find PIM unit command
 *
 * @author JM Leroux <jean-marie.leroux@akeneo.com>
 */
class FindUnitCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pim:measures:find')
            ->setDescription('Find a PIM unit.')
            ->addArgument(
                'unit',
                InputArgument::REQUIRED,
                'The measure or unit to search for.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $unit = $input->getArgument('unit');
        $this->write(sprintf('Search for measure <info>%s</info>', $unit));
        $mapper = $this->getContainer()->get('pim_extended_measures.mapper');
        $measure = $mapper->getPimUnit($unit);
        dump($measure);
    }

    /**
     * @param string $message
     */
    private function write($message)
    {
        $this->output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message));
    }
}
