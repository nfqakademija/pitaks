<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.25
 * Time: 16:23
 */

namespace pitaks\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use pitaks\TeamBundle\Entity\TeamReservation;
use pitaks\UserBundle\Entity\LastReviews;
use Proxies\__CG__\pitaks\KickerBundle\Entity\RegisteredReservation;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pitaks\UserBundle\Entity\User;
class ProfileController extends BaseController
{

    public function showAction()
    {/** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        //ranks
        $sum = 0;
        $score = 0;
        if($user->getRank()) {
            $rank = $this->get('rank_service')->findHigherRank($user->getRank());
            $stat = $this->getAllUserStat($user);
            if ($rank) {
                $sum = $rank->getWin() - $stat->getGamesWon();
                $score = $rank->getScored() - $stat->getPointsScored();
            }
        }
        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,'nextRank' => $rank,'sum'=> $sum, 'score' =>$score
        ));
    }

    /**
     * @param $user
     * @return \pitaks\UserBundle\Model\UserAllStatistic
     */
    protected function getAllUserStat($user)
    {
        return $this->get('user_statistic_service')->returnAllUserStatistic($user);

    }

    public function userSentReservationsAction()
    {
        $em = $this->getDoctrine();
        $user = $this->getUser();
        $username = $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('userId' => $username ,'isConfirmed' => false), array('reservationStart' => 'ASC'));
        $userReservations = array();
        foreach ($reservations as $reservation) {
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getFriendId(),
                "confirmed" => $reservation->getIsConfirmed()
            );
            $userReservations[] = $record;
        }
        return $this->render('@User/Reservations/userReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user' => $user
        ));
    }
    /*need to get all reservations*/
    public function userRegisteredReservationsAction()
    {
        $em = $this->getDoctrine();
        $user = $this->getUser();
        $username = $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('userId' => $username ,'isConfirmed' => true), array('reservationStart' => 'ASC'));
        $userReservations = array();
        foreach ($reservations as $reservation) {
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getFriendId(),
                "confirmed" => $reservation->getIsConfirmed()
            );
            $userReservations[] = $record;
        }

        $reservations2 = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username, 'isConfirmed' => true), array('reservationStart' => 'ASC'));
        foreach ($reservations2 as $reservation) {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getUserId(),
                "confirmed" => $reservation->getIsConfirmed()
            );
            $userReservations[] = $record;
        }
        return $this->render('@User/Reservations/userReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user' => $user
        ));

    }

    /**
     * @return JsonResponse
     */
    public function deleteUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->deleteRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was deleted " . $reservationId);
    }


    public function userUnconfirmedRegisteredReservationsAction()
    {
        $em = $this->getDoctrine();
        $user = $this->getUser();
        $username = $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username, 'isConfirmed' => false));
        $userReservations = array();
        foreach ($reservations as $reservation) {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getUserId()
            );
            $userReservations[] = $record;
        }
        $this->get('user_lastviews_service')->updateUserChallengeReviewData($user);
        return $this->render('@User/Reservations/userUncorfirmedReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user' => $user
        ));
    }

    /**
     * @return JsonResponse
     */
    public function acceptUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->acceptUnconfirmedRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was accepted " . $reservationId);
    }

    public function upcomingUserReservationsAction()
    {
        $userRes = $this->get('reservation_service')->upcomingUserReservations($this->getUser());
        $userTeamsRes = $this->get('reservation_service')->upcomingUserTeamsReservations($this->getUser());
       
        $startReservationUser=null;
        $startReservationUserTeam=null;
        $reservationFirstTime=null;
        $isUserReservation=true;
        if($userRes) {
            /**
             * @var RegisteredReservation $userFirst
             */
            $userFirst = $userRes[0];
            $startReservationUser = $userFirst->getReservationStart();
        }
        if($userTeamsRes){
            /**
             * @var  TeamReservation $teamFirst
             */
            $teamFirst= $userTeamsRes[0];
            $startReservationUserTeam = $teamFirst->getReservationStart();
        }
        if($startReservationUserTeam && $startReservationUser){
            if( $startReservationUserTeam<$startReservationUser){
                $isUserReservation = false;
                $reservationFirstTime=$startReservationUserTeam;
            }
            else
                $reservationFirstTime=$startReservationUser;
        }
        elseif($startReservationUser)
            $reservationFirstTime=$startReservationUser;
        elseif($startReservationUserTeam ){
            $isUserReservation = false;
            $reservationFirstTime=$startReservationUserTeam;
        }
        return $this->render('UserBundle:Reservations:upcomingUserReservations.html.twig',
            array( 'userReservations' => $userRes,'userTeamsReservations' =>$userTeamsRes,
                'firstReservation' => $reservationFirstTime, 'isUserReservation' => $isUserReservation));
    }

}

