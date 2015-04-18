<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.04
 * Time: 20:19
 */

namespace pitaks\KickerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ApiCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('Api')
            ->setDescription('get data from api and put it in database. Searching by table id')
            ->addArgument(
                'id',
                InputArgument::OPTIONAL,
                'table ID value'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');

        /** need to check if id exits */
        $api = $this->getContainer()->get('api_data');
        $api->getData($id);
        $output->writeln("No more records ...");

    }
}