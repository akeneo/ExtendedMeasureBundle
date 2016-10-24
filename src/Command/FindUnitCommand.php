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
        $unit = $input->getArgument('unit');
        $this->write($output, sprintf('Search for measure <info>%s</info>', $unit));
        $resolver = $this->getContainer()->get('pim_extended_measures.repository');
        $measure = $resolver->findByUnit($unit);
        $this->write($output, sprintf('Family = <info>%s</info>', $measure['family']));
        $this->write($output, sprintf('Unit = <info>%s</info>', $measure['unit']));
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
