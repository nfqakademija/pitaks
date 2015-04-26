<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 01:52
 */

namespace pitaks\KickerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegularReservationDeleteCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('pitaks:regularReservationDelete')
            ->setDescription('reservationBlocks');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('reservation_service')->regularDeleting();
        $output->writeln("Reservations was deleted...");
    }

}