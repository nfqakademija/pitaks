<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.10
 * Time: 23:25
 */

namespace pitaks\KickerBundle\Command;


use pitaks\UserBundle\Entity\Rank;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectCreateCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('pitaks:createProject')
            ->setDescription('reservationBlocks');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $count = count($em->getRepository('UserBundle:Rank')->findAll());
        if($count==0) {
            $rank = new Rank();
            $rank->setName('begemotas');
            $rank->setWin(0);
            $rank->setScored(0);
            $em->persist($rank);
            $em->flush();
        }
        $output->writeln("created Base objects");
    }

}