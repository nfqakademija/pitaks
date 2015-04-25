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
     * @param Request $request
     * @return Response
     */
    public function viewAction(Request $request)
    {

        $tableId = $this->get('request')->request->get('tableId');
        $table = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        return $this->render('pitaksKickerBundle:Reservation:editReservation.html.twig', array(
            'table' => $table
        ));
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function timeListAction(Request $request)
    {
        $date = $this->get('request')->request->get('dateValue');
        $startTime = $this->get('request')->request->get('startDate');
        $duration = $this->get('request')->request->get('duration');
        $tableId = $this->get('request')->request->get('tableId');

        $reservations = $this->get('reservation_service')->
        showFreeTableReservations($tableId,$date,$duration,$startTime);

        return $this->render('pitaksKickerBundle:Reservation:timeList.html.twig', array(
                'time' => $reservations, 'data' => $date, 'tableId' => $tableId
            )
        );
    }

    public function isTableFreeAction($tableId)
    {
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $api = $this->get('api_data')->getTableStatusFromApi($table);
        $game = $this->getDoctrine()->getManager()->getRepository('pitaksKickerBundle:Game')->
        getLastGame($table->getId());
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
            $date = $this->get('request')->request->get('dateValue');
            $tableId = $this->get('request')->request->get('tableId');
            $startValue =  $this->get('request')->request->get('startValue');
            $endValue =  $this->get('request')->request->get('endValue');
            $this->get('reservation_service')-> saveUserReservation($date, $tableId, $startValue,$endValue);
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