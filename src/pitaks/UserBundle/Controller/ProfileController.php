<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.25
 * Time: 16:23
 */

namespace pitaks\UserBundle\Controller;

use GuzzleHttp\Message\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends BaseController{

    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }

    /*need to get all reservations*/
    public function userRegisteredReservationsAction()
    {
        $em=$this->getDoctrine();
        $user=  $this->getUser();
        $username=  $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('userId' => $username),array('reservationStart'=>'ASC'));
        $userReservations= array();
        foreach($reservations as $reservation)
        {
            $tableId=$em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId'=>$reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" =>$tableName,
                "startDate" =>$reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" =>$reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" =>$tableId,
                "friend" =>$reservation->getFriendId(),
                "confirmed" =>$reservation->getIsConfirmed()
            );
            $userReservations[]=$record;
        }

        $reservations2 = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username, 'isConfirmed'=>true),array('reservationStart'=>'ASC'));
        foreach($reservations2 as $reservation)
        {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId=$em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId'=>$reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" =>$tableName,
                "startDate" =>$reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" =>$reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" =>$tableId,
                "friend" =>$reservation->getUserId(),
                "confirmed" =>$reservation->getIsConfirmed()
            );
            $userReservations[]=$record;
        }
        return $this->render('@User/Reservations/userReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user'=>$user
        ));

    }

    /**
     * @return JsonResponse
     */
    public function deleteUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->deleteRegisteredReservation($reservationId);
        return new JsonResponse( "Reservation was deleted ".$reservationId );
    }


    public function userUnconfirmedRegisteredReservationsAction()
    {
        $em=$this->getDoctrine();
        $user=  $this->getUser();
        $username=  $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username,'isConfirmed' =>false ));
        $userReservations= array();
        foreach($reservations as $reservation)
        {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId=$em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId'=>$reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" =>$tableName,
                "startDate" =>$reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" =>$reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" =>$tableId,
                "friend" =>$reservation->getUserId()
            );
            $userReservations[]=$record;
        }
        return $this->render('@User/Reservations/userUncorfirmedReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user'=>$user
        ));

    }
    /**
     * @return JsonResponse
     */
    public function acceptUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->acceptUnconfirmedRegisteredReservation($reservationId);
        return new JsonResponse( "Reservation was accepted ".$reservationId );
    }
}