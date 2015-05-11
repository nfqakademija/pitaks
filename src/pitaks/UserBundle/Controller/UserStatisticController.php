<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 01:11
 */

namespace pitaks\UserBundle\Controller;


use pitaks\KickerBundle\Entity\Game;
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

        return $this->render('@User/Statistic/userStatTable.html.twig',
            array('statsForTables' => $statsForTables, 'stats' => $stats, 'user' =>$this->getUser()));
    }

    public function lastTenUserGamesAction()
    {
        //pasiduosime useri is kurio cardId nuskaitysime 10 paskutiniu jo zaidimu
        $user=$this->getUser();
        $games = $this->get('user_statistic_service')->getLastUserGames($user,5);
        $info = array();
        foreach($games as $game)
        {
            /** @var Game $game */
            //0 pirma 1-antra
            $friend =null;
            $competitor1=null;
            $competitor2=null;
            if($this->get('user_statistic_service')->whichTeamUserBelongTo($user,$game) == 0)
            {
                $friend=null;
                $competitor2=null; $competitor1=null;
                $myResult = $game->getScoreTeam1();
                $enemyResult = $game->getScoreTeam2();
                if($user->getCardId() == $game->getUser1Team1()) {
                    if($game->getUser2Team1()!= null)
                    $friend = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser2Team1());
                }
                else{
                    if($game->getUser1Team1()!= null)
                    $friend = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser1Team1());

                }
                if($game->getUser1Team2()!= null)
                $competitor1 = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser1Team2());

                if($game->getUser2Team2()!= null)
                $competitor2 = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser2Team2());

            }
            else{
                $myResult = $game->getScoreTeam2();
                $enemyResult = $game->getScoreTeam1();
                if($user->getCardId() == $game->getUser1Team2()) {
                    if($game->getUser2Team2()!= null)
                    $friend = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser2Team2());
                }
                else{
                    if($game->getUser1Team2()!= null)
                    $friend = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser1Team2());

                }
                if($game->getUser1Team1()!= null)
                $competitor1 = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser1Team1());
                if($game->getUser2Team1()!= null)
                $competitor2 = $this->get('fos_user.user_manager')->findUserByCardId($game->getUser2Team1());
            }
            $gameDate = date('Y-m-d H:i', $game->getBeginTime());
            $gameDuration = date('H:i:s',$game->getLastTime() - $game->getBeginTime());
            $gameInfo = array(
                'myResult' => $myResult,
                'enemyResult' => $enemyResult,
                'game' => $game,
                'friend' => $friend,
                'competitor1' => $competitor1,
                'competitor2' => $competitor2,
                'gameDate' => $gameDate,
                'gameDuration' =>$gameDuration,
            );
            $info[] = $gameInfo;
        }

        return $this->render('UserBundle:Statistic:lastNUserGamesList.html.twig',
            array('games' => $info)
        );
    }

}