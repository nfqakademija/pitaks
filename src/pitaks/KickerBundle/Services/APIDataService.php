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
 use pitaks\KickerBundle\Event\ApiEvents;
 use Symfony\Component\Config\Definition\Exception\Exception;
 use Symfony\Component\DependencyInjection\ContainerAware;
 use pitaks\KickerBundle\EventListener\ApiEventListener;
 use Symfony\Component\EventDispatcher\EventDispatcher;

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
      * @param Table $table
      * @return mixed
      */
        public function getLastActiveTableApiJson($table)
        {
            return $this->getJsonFromTableApi($table,1);
        }
     /**
      * @param Tables $table
      * @param $rows
      * @param null $fromID
      * @return mixed
      */
    public function getJsonFromTableApi($table, $rows, $fromID = null)
    {
        $client = new Client();
        $results= null;

        $dispatcher= $this->container->get('event_dispatcher');
        $event = new ApiEvents();

        try {
            if (!$fromID) {
                $results = $client->
                get($table->getApiUrl() . 'rows=' . $rows, ['auth' => [$table->getUserName(), $table->getPassword()]]);
                $dispatcher->dispatch('api_success', $event); }
            else {
                $results = $client->
                get($table->getApiUrl() . 'rows=' . $rows . '&from-id=' . $fromID, ['auth' => [$table->getUserName(), $table->getPassword()]]);
                $dispatcher->dispatch('api_success', $event);
            }

        }
        catch(Exception $e){
            echo 'Caught exceptions: ',  $e->getMessage(), "\n";
            $dispatcher->dispatch('api_failed', $event);
        }
        return $results->json();
    }
    /**
     * @param $taleID
     */
    public function  getData($taleID)
    {
       /** need to check if id exits */
      if($this->getEm()->getRepository('pitaksKickerBundle:Tables')->checkTableId($taleID)) {
         //we will execute code where:
         set_time_limit (0);
         $table =$this->getEm()->getRepository('pitaksKickerBundle:Tables')->findByID($taleID);
         $fromID=$this->getEm()->getRepository('pitaksKickerBundle:EventTable')->getLastEvent($taleID);
         $data = $this->getLastActiveTableApiJson($table);

         $lastID= $data['records'][0]['id'];
         while($fromID<$lastID)
         {
             //set API address, later we will take it from tables class
            $data =  $data = $this->getJsonFromTableApi($table, 100, $fromID);
             $records = $data['records'];
             //we go
             foreach ($records as $record) {
                 //create new event record
                 $event = new EventTable();
                 $event->setData($record["data"])
                     ->setId($record['id'])
                     ->setTableId($table)
                     ->setTimeSec($record["timeSec"])
                     ->setType($record["type"])
                     ->setUsec($record["usec"]);

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
    }}
    else
    {
    echo("Invalid table id ".$taleID);
    }

}


}