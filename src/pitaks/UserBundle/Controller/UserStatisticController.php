<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 01:11
 */

namespace pitaks\UserBundle\Controller;


use Symfony\Component\HttpFoundation\Response as GoodResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserStatisticController extends Controller{

    public function showAllLoginUserStatisticAction($username)
    {

        $user = $this->get('fos_user.user_manager')->findUserByUsername($username);
        if($user) {
            $statsService = $this->get('user_statistic_service');
            $stats = $statsService->returnAllUserStatistic($user);
            $statsForTables = $statsService->returnAllUserStatisticForEachTable($user);

            return $this->render('UserBundle:Statistic:LoginUserStatistic.html.twig',
                array('statsForTables' => $statsForTables, 'stats' => $stats, 'user' => $user));
        }
        /*Reike returnnint Koki view klaidu*/
        return new GoodResponse("<h2>User with username: ".$username."  doesn't exit</h2>");
    }
    public function myStatsAction()
    {
        $statsService = $this->get('user_statistic_service');
        $stats = $statsService->returnAllUserStatistic($this->getUser());
        $statsForTables = $statsService->returnAllUserStatisticForEachTable($this->getUser());

        return $this->render('UserBundle:Statistic:LoginUserStatistic.html.twig',
            array('statsForTables' => $statsForTables, 'stats' => $stats, 'user' =>$this->getUser()));
    }


}