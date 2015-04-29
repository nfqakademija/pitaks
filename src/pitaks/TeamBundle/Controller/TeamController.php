<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.29
 * Time: 00:41
 */
namespace pitaks\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeamController extends Controller{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function TeamsViewAction()
    {
        $teams = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->findAll();
        return $this->render(
            'pitaksTeamBundle:TeamViews:allTeamsView.html.twig',
            array('teams' => $teams)
        );
    }

    /**
     * @param integer $user
     */
    public function createTeamAction($user)
    {
        /*select player and add team name*/


    }



}