<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.25
 * Time: 14:50
 */

namespace pitaks\KickerBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateReservationBlocksCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('pitaks:createReservationBlocks')
            ->setDescription('reservationBlocks');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('reservation_service')->createRegistrationBlocksForTables();
        $output->writeln("Reservation Blocks Created...");
    }

}