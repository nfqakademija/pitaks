<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Entity\EventTable;

class TableController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/createTable")
     * @Template()
     */
    public function createTableAction()
    {
        $table = new Tables();
        $table->setName("VilniusNFQ");
        $table->setApiUrl('http://wonderwall.ox.nfq.lt/kickertable/api/v1/events?rows=5');

        /*Reikalingas objektas bendravimui su duombaze*/
        $em = $this->getDoctrine()->getManager();
        /*Metodas pasakantis vykdtyti ta objekta*/
        $em->persist($table);
        /*Ideda objekta*/
        $em->flush();

        return new Response('Sukurtas stalas:  '.$table->getId()."pavadiniams: ".$table->getName());

    }

    /**
     * @Route("/getTableEvents")
     * @Template()
     */
    public function getTableEventsAction()
    {
        return array(
                // ...
            );

    }

    public function setEventsIDAction()
    {
        $em = $this->getDoctrine()->getManager();
        $eventai = $em->getRepository('pitaksKickerBundle:EventTable')->findALL();
        foreach ($eventai as $eventas) {
            $eventas->setTableId(1);
            $em->flush();
        }
        return new Response('<html><body>Pakeisti Duomenys</body></html>');
    }

    /**
     * @return Response
     */
    public  function readEventAction()
    {
        set_time_limit (100000000000000000000000000);
        $client = new Client();
        $fromID=$this->getDoctrine()->getManager()->getRepository('pitaksKickerBundle:EventTable')->getLastEvent();

        $rows=100;

        $resa = $client->get('http://wonderwall.ox.nfq.lt/kickertable/api/v1/events?rows=1', ['auth' =>  ['nfq', 'labas']]);
        $data = $resa->json();

        echo "paskutinis id : ".$paskutinisID= $data['records'][0]['id'];

        while($fromID<$paskutinisID)
        {
            //set API address, later we will take it from tables class
            $res = $client->get('http://wonderwall.ox.nfq.lt/kickertable/api/v1/events?rows='.$rows.'&from-id='.$fromID, ['auth' =>  ['nfq', 'labas']]);
            $data = $res->json();
            //
            $irasai = $data['records'];
            //we go
            foreach ($irasai as $irasas) {

                $em = $this->getDoctrine()->getManager();
                $Eventas = new EventTable();
                $Eventas->setId($irasas['id']);
                $Eventas->setTimeSec($irasas["timeSec"]);
                $Eventas->setUsec($irasas["usec"]);
                $Eventas->setType($irasas["type"]);
                $Eventas->setData($irasas["data"]);
                //later we need to change it into table's id
                $Eventas->setTableId(1);
                //no brand found. So, persist the new one:
                $em->persist($Eventas);
                /*Ideda objekta*/
                $em->flush();
            }
            //last id from records
            $array_lenght  = count($data['records']);
            //set next id
            $fromID= $data['records'][$array_lenght-1]['id'];
            //echo new id
            echo "ID kinta: ".$fromID."<br/>";
        }

        return new Response('<html><body>Hello !</body></html>');
    }
}
