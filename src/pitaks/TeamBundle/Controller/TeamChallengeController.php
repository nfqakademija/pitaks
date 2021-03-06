<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.02
 * Time: 13:26
 */

namespace pitaks\TeamBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TeamChallengeController extends Controller{

    /**
     * @param $teamId
     * @param $anotherTeamId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ChallengePageAction($teamId,$anotherTeamId)
    {
        $myTeam =$this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $friendTeam = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($anotherTeamId);
        $tables = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->findAll();
        return $this->render('pitaksTeamBundle:TeamChallenge:TeamChallengeView.html.twig', array(
                'tables' => $tables , 'friend' => $friendTeam, 'myteam' => $myTeam
            )
        );
    }

    /**
     * @param $teamId
     * @param $anotherTeamId
     * @return Response
     */
    public function saveChallengeAction($teamId,$anotherTeamId)
    {
        $date = $this->get('request')->request->get('dateValue');
        $tableId = $this->get('request')->request->get('tableId');
        $startValue =  $this->get('request')->request->get('startValue');
        $endValue =  $this->get('request')->request->get('endValue');
       $my= $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $friend = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($anotherTeamId);
        $this->get('reservation_service')->saveTeamReservation($date, $tableId, $startValue,$endValue,$my,$friend);
        return new Response("Challenged ". $friend->getName());
    }

    /**
     * @param $teamId
     * @return Response
     */
    public function showSendedChallengesTeamsAction($teamId)
    {
       $reservations = $this->getDoctrine()->getRepository('pitaksTeamBundle:TeamReservation')->findBy(array('team' => $teamId, 'isConfirmed'=>false));
        return $this->render('pitaksTeamBundle:TeamChallenge:reviewTeamChallenges.html.twig',
            array('reservations' => $reservations, 'teamId' =>$teamId) );

    }

    /**
     * @param $teamId
     * @return Response
     */
    public function showReceivedChallengesTeamsAction($teamId)
    {
        $reservations = $this->getDoctrine()->getRepository('pitaksTeamBundle:TeamReservation')->findBy(array('competitorTeam' => $teamId, 'isConfirmed'=>false));
        return $this->render('pitaksTeamBundle:TeamChallenge:reviewTeamChallenges.html.twig',
            array('reservations' => $reservations, 'teamId' =>$teamId) );
    }

    /**
     * @param $teamId
     * @return Response
     */
    public function showConfirmedChallengesTeamsAction($teamId)
    {
        $reservations = $this->getDoctrine()->getRepository('pitaksTeamBundle:TeamReservation')->getConfirmedReservations($teamId);
        return $this->render('pitaksTeamBundle:TeamChallenge:reviewTeamChallenges.html.twig',
            array('reservations' => $reservations, 'teamId' =>$teamId) );
    }

    /**
     * @param integer $teamId
     * @return Response
     */
    public function getTeamUnconfirmedChallengesCountAction($teamId)
    {
        $count = count($this->getDoctrine()->getRepository('pitaksTeamBundle:TeamReservation')->
        findBy(array('competitorTeam' => $teamId, 'isConfirmed'=>false)));
        return new Response($count);
    }


    /**
     * @return JsonResponse
     */
    public function acceptTeamReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->acceptUnconfirmedTeamRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was accepted " . $reservationId);
    }

    /**
     * @return JsonResponse
     */
    public function deleteTeamReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->deleteTeamRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was deleted " . $reservationId);
    }
}