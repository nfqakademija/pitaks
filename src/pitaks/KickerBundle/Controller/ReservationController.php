<?php

namespace pitaks\KickerBundle\Controller;

use pitaks\KickerBundle\Entity\Reservation;
use pitaks\KickerBundle\Event\ApiEvents;
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAction(Request $request)
    {

        if ($this->get('request')->isXmlHttpRequest()) {

            $date = $this->get('request')->request->get('dateValue');
            $tableId=$this->get('request')->request->get('tableId');

            $em = $this->getDoctrine()->getManager();
            $reservations = $em->getRepository('pitaksKickerBundle:Reservation')->find(1);

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






        }# endif this is an ajax request

    } #end of the controller.



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
        $dispatcher = new EventDispatcher();
        $apiEvent = new ApiEvents();

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