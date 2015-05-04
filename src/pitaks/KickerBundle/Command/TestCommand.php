<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.02
 * Time: 19:01
 */

namespace pitaks\KickerBundle\Command;

use pitaks\KickerBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pitaks:test')
            ->setDescription('Test');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $game = new Game();
        $game->setTableId(4);
        $game->setBeginTime(1);
        $game->setEndEventId(15);
        $game->setLastAddedEventId(54);
        $game->setLastTime(14);
        $game->setScoreTeam1(10);
        $game->setScoreTeam2(7);
        $game->setUser1Team1(2);
        $game->setUser1Team2(3);
        $game->setUser2Team1(4);
        $this->getContainer()->get('user_statistic_service')->addUserStatistic($game);
    }

}