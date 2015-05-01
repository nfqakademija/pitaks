<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.01
 * Time: 21:06
 */

namespace pitaks\TeamBundle\Controller;


use pitaks\TeamBundle\Entity\TeamStatistic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeamStatsController extends Controller{
    /**
     * @param $teamId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showEachStatsForTableAction($teamId)
    {
        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        if(!$team)
        {
            //no team exit
           return $this->redirectToRoute('fos_user_profile_show');
        }
        else{

            return $this->render('pitaksTeamBundle:TeamStatsViews:teamEachTableStatView.html.twig', array(
            'stats' =>$team->getStats()
        ));}
    }

    /**
     * @param $teamId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAllTeamStatisticAction($teamId)
    {
        $team= $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        if(!$team)
        {
            //throws exeption
            return $this->redirectToRoute('fos_user_profile_show');
        }
        else {
            $statistic = $this->get('team_statistic_service')->returnAllTeamStatistic($team);
            return $this->render('pitaksTeamBundle:TeamStatsViews:teamStatsView.html.twig', array(
                'stats' => $statistic));
        }
    }
}