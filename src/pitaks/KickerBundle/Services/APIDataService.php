<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.04
 * Time: 21:29
 */
 namespace pitaks\KickerBundle\Services;


 use Doctrine\ORM\EntityManager;
 use GuzzleHttp\Client;
 use pitaks\KickerBundle\Entity\Tables;
 use pitaks\KickerBundle\Entity\EventTable;
 use Symfony\Component\DependencyInjection\ContainerAware;

class APIDataService extends ContainerAware {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }


    /**
     * @param $taleID
     */
    public function  getData($taleID)
{
       /** need to check if id exits */
     if($this->getEm()->getRepository('pitaksKickerBundle:Tables')->checkTableId($taleID)) {
         //we will execute code where:
         set_time_limit (100000000000000000000000000);
         $table =$this->getEm()->getRepository('pitaksKickerBundle:Tables')->findByID($taleID);

         $client = new Client();
         $fromID=$this->getEm()->getRepository('pitaksKickerBundle:EventTable')->getLastEvent();
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
                 $event = new EventTable($record['id'],$record["timeSec"],$record["usec"],$record["type"],$record["data"],$taleID);
                 //no brand found. So, persist the new one:
                 $this->getEm()->persist($event);
                 /*Ideda objekta*/
                 $this->getEm()->flush();
        }
        //last id from records
        $array_lenght  = count($data['records']);
        //set next id
        $fromID= $data['records'][$array_lenght-1]['id'];
        //echo new id
        echo('Last inserted id from API: '.$fromID);
    }
    }
    else
    {
    echo("Invalid table id ".$taleID);
    }

}


}