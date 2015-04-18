<?php

namespace pitaks\KickerBundle\Controller;

use pitaks\KickerBundle\Entity\Game;
use pitaks\KickerBundle\Entity\Reservation;
use pitaks\KickerBundle\Form\Type\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pitaks\KickerBundle\Entity\Tables;



class ReservationController extends Controller
{
    /**
     * @return Response
     */
    public function viewAction()
    {
        return $this->render('pitaksKickerBundle:Reservation:editReservation.html.twig');
    }


    //reiektu gauti data ir pagal data susirast reservacijas ir atsivaizduot
    /**
     * @return Response
     */
    public function timeListAction(Request $request)
    {
        if ($this->get('request')->isXmlHttpRequest()) {

            $date = $this->get('request')->request->get('dateValue');
            $tableId = $this->get('request')->request->get('tableId');
            $em = $this->getDoctrine()->getManager();
            $datos = $em->getRepository('pitaksKickerBundle:Reservation')->freeTimeArray($tableId, $date);
            return $this->render(
                'pitaksKickerBundle:Reservation:timeList.html.twig', array(
                    'time' => $datos, 'data' => $date
                )

            );
        }
    }

    public function isTableFreeAction($tableId)
    {
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $api = $this->get('api_data')->getTableStatusFromApi($table);
        $game = $this->getDoctrine()->getManager()->getRepository('pitaksKickerBundle:Game')->getLastGame($table->getId());
        $game->getScoreTeam1();
        $game->getScoreTeam2();
        if($api == true){
            echo "Table is busy: ".$n = rand(0,100000);}
        else{
            echo "Table is free: <br/> LastREsulst: ". $game->getScoreTeam1()." : ".$game->getScoreTeam2();
            }
        return new Response();
    }

    public function saveReservationAction(Request $request)
    {
        if ($this->get('request')->isXmlHttpRequest()) {

            $date = $this->get('request')->request->get('dateValue');
            $tableId = $this->get('request')->request->get('tableId');
            $timeValue =  $this->get('request')->request->get('timeValue');

            echo 'gauta data: '.$date.' laikas: '.$timeValue;

          $timeEnd = date('H:i', (strtotime($timeValue) + 3600));
            $em = $this->getDoctrine()->getManager();
            $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);

            $reservation = new Reservation();
            $datenow = new \DateTime();

            $reservation->setDate($datenow);
            $reservation->setTableId($table);
            $reservation->setUserId(1); //need to make dinamic


            $s = $date." ".$timeValue;
            $b =new \DateTime($s);
            $s1 = $date." ".$timeEnd;
            $b1 =new \DateTime($s1);
            $reservation->setFriendId(1);
            $reservation->setConfirmed(false);
            $reservation->setReservationStart( $b);
            $reservation->setReservationEnd( $b1 );
            $reservation->setReservatioDuration(1); //need to make dinamic*/
            $em->persist($reservation);
            $em->flush();

        }
        return new Response("patalpinta");
    }

    /**
     * @param Request $request
     * @return Response
     *
     */
    public function ajaxAction(Request $request)
    {

        if ($this->get('request')->isXmlHttpRequest()) {

            $date = $this->get('request')->request->get('dateValue');
            $tableId=$this->get('request')->request->get('tableId');

            $em = $this->getDoctrine()->getManager();
            $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
            //return table list
            if (!$table) {
                throw $this->createNotFoundException(
                    'No table found for id ' . $tableId
                );
            }
            $data = $em->getRepository('pitaksKickerBundle:Reservation')->findByDate($tableId,$date);
            return $this->render(
                'pitaksKickerBundle:Reservation:index.html.twig',
                array('reservations' => $data, 'table' => $table)
            );
        }

    }



    /**
     * @param $tableId
     * @return Response
     */
    public function reservationListAction($tableId)
    {
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);

        //return table list
        if (!$table) {
            throw $this->createNotFoundException(
                'No table found for id ' . $tableId
            );
        }
        $data = $table->getReservations()->toArray();
        return $this->render(
            'pitaksKickerBundle:Reservation:index.html.twig',
            array('reservations' => $data, 'table' => $table)
        );


    }
//later we need to add user data
    /**
     * @return Response
     */
    public function newReservationAction(Request $request, $tableId)
    {
        $reservation = new Reservation();
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $form = $this->createForm(new ReservationType(), $reservation);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $reservation->setTableId($table);
            //base value for now
            $reservation->setUserId(1);
            $date = new \DateTime();
            $reservation->setDate($date);
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('reservationList', array(
                'tableId' => $tableId
            ));
        }
        return $this->render('pitaksKickerBundle:Reservation:newReservation.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     */
    public function deleteReservationAction()
    {
        return $this->render('pitaksKickerBundle:Reservation:deleteReservation.html.twig', array(// ...
        ));
    }

    /**
     * @param Request $request
     * @param $tableId
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editReservationAction(Request $request, $tableId, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('pitaksKickerBundle:Reservation')->find($id);
        if (!$reservation) {
            throw $this->createNotFoundException(
                'Bad data' . $id
            );
        }
        $form = $this->createForm(new ReservationType(), $reservation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $date = new \DateTime();
            $reservation->setDate($date);
            $em->flush();

            return $this->redirectToRoute('reservationList', array(
                'tableId' => $tableId
            ));
        }
        return $this->render('pitaksKickerBundle:Reservation:newReservation.html.twig', array(
                'form' => $form->createView(),
        ));
    }
}