<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.04
 * Time: 09:24
 */

namespace pitaks\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserCountsController extends Controller{
    /**
     * @return Response
     */
    public function userNewReservationCountAction()
    {
        $user = $this->getUser();
        $count = $this->get('user_lastviews_service')->getUserChallengesCountFromLastVisit($user);
        return new Response($count);
    }

    /**
     * @return Response
     */
    public function userNewTeamsCountAction()
    {
        $user = $this->getUser();
        $count = $this->get('user_lastviews_service')->getUserNewTeamsSuggestionCount($user);
        return new Response($count);
    }
}