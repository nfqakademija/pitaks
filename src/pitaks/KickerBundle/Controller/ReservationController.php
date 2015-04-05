<?php

namespace pitaks\KickerBundle\Controller;

use pitaks\KickerBundle\Entity\Reservation;
use pitaks\KickerBundle\Form\Type\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pitaks\KickerBundle\Entity\Tables;

class ReservationController extends Controller
{
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