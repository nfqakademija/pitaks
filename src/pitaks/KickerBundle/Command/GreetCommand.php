<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.01
 * Time: 22:27
 */
namespace pitaks\KickerBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GuzzleHttp\Client;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Entity\EventTable;
use pitaks\KickerBundle\Entity\EventTableRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GreetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('getApiForTable')
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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** need to check if id exits */

       if($em->getRepository('pitaksKickerBundle:Tables')->checkTableId($id)) {
            //we will execute code where:
            set_time_limit (100000000000000000000000000);

            $table =$em->getRepository('pitaksKickerBundle:Tables')->findByID($id);

            $client = new Client();
            $fromID=$em->getRepository('pitaksKickerBundle:EventTable')->getLastEvent();
            $rows=100;

            $results = $client->get($table->getApiUrl().'rows=1', ['auth' =>  [$table->getUserName(),  $table->getPassword()]]);
            $data = $results->json();
            $lastID= $data['records'][0]['id'];

            while($fromID<$lastID)
            {
                //set API address, later we will take it from tables class
                $res = $client->get($table->getApiUrl().'rows='.$rows.'&from-id='.$fromID, ['auth' =>  [$table->getUserName(),  $table->getPassword()]]);
                $data = $res->json();
                $records = $data['records'];
                //we go
                foreach ($records as $record) {
                    //create new event record
                    $event = new EventTable($record['id'],$record["timeSec"],$record["usec"],$record["type"],$record["data"],$id);
                    //no brand found. So, persist the new one:
                    $em->persist($event);
                    /*Ideda objekta*/
                    $em->flush();
                }
                //last id from records
                $array_lenght  = count($data['records']);
                //set next id
                $fromID= $data['records'][$array_lenght-1]['id'];
                //echo new id
                $output->writeln('Last inserted id from API: '.$fromID);
            }
        }
        else
        {
            $output->writeln("Invalid table id ".$id);
        }
        $output->writeln("No more records ...");
    }
}