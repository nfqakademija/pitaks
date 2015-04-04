<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.02
 * Time: 19:01
 */

namespace pitaks\KickerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('pitaks:test')
            ->setDescription('Test');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Test");
    }

}