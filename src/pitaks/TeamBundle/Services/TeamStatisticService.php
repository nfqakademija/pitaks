<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.01
 * Time: 18:00
 */

namespace pitaks\TeamBundle\Services;
use Doctrine\ORM\EntityManager;
use pitaks\KickerBundle\Entity\Game;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\TeamBundle\Entity\Team;
use pitaks\TeamBundle\Entity\TeamStatistic;
use Symfony\Component\DependencyInjection\ContainerAware;

class TeamStatisticService extends ContainerAware{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param Game $game
     */
    public function addTeamStatistic($game)
    {
        $card11 = $game->getUser1Team1();
        $card12 = $game->getUser2Team1();
        $card21 = $game->getUser1Team2();
        $card22 = $game->getUser2Team2();
        $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->find($game->getTableId());
        if($card11 && $card12 || $card21 && $card22) {
            $user11 = $this->container->get('fos_user.user_manager')->findUserByCardId($card11);
            $user12 = $this->container->get('fos_user.user_manager')->findUserByCardId($card12);
            if($user11 && $user12){
                $team1Exits = $this->container->get('team_service')->checkIfTeamExits($user11->getId(), $user12->getId());
                if($team1Exits){
                    $team1= $this->container->get('team_service')->findTeamByTwoUser($user11,$user12);
                    $this->setTeamData($game,0,$team1,$table);
                }
            }
            $user21 = $this->container->get('fos_user.user_manager')->findUserByCardId($card21);
            $user22 = $this->container->get('fos_user.user_manager')->findUserByCardId($card22);
            if($user21 && $user22) {
                $team2Exits = $this->container->get('team_service')->checkIfTeamExits($user21->getId(), $user22->getId());
                if ($team2Exits) {
                    $team2 = $this->container->get('team_service')->findTeamByTwoUser($user21, $user22);
                    $this->setTeamData($game, 1, $team2, $table);
                }
            }

        }
    }

    /**
     * @param Game $game
     * @param integer $teamNumber
     * @param Team $team
     * @param Tables $table
     */
    public function setTeamData($game, $teamNumber, $team,$table)
    {
        $teamStatistic = $this->getEm()->getRepository('pitaksTeamBundle:TeamStatistic')
            ->findOneBy(array('team' => $team->getId(), 'table' => $table->getId()));
        if($teamStatistic == null){
            $teamStatistic = new TeamStatistic();
            $team->addStat($teamStatistic);
            $table->addTableTeamStatistic($teamStatistic);
            $teamStatistic->setTeam($team);
            $teamStatistic->setTable($table);
            $this->getEm()->persist($teamStatistic);
            $this->getEm()->flush();
        }
        $teamStatistic->increaseGamesCount();
        //if user belong to the first team
        if($teamNumber == 0) {
            if ($game->getScoreTeam1() > $game->getScoreTeam2()) {
                $teamStatistic->increaseWinGameCount();
            }
            $teamStatistic->setPointsScored($game->getScoreTeam1()+ $teamStatistic->getPointsScored());
            $teamStatistic->setPointsMissed($game->getScoreTeam2()+ $teamStatistic->getPointsMissed());
            $this->getEm()->flush();
        }
        else
        {
            if ($game->getScoreTeam1() < $game->getScoreTeam2()) {
                $teamStatistic->increaseWinGameCount();
            }
            $teamStatistic->setPointsScored($game->getScoreTeam2()+ $teamStatistic->getPointsScored());
            $teamStatistic->setPointsMissed($game->getScoreTeam1()+ $teamStatistic->getPointsMissed());
            $this->getEm()->flush();
        }
    }


    /**
     * @param Team $team
     * @return array
     */
    public function returnAllTeamStatistic($team)
    {
        $statistic = array(
            "gamePlayed" => 0,
            "gameWon" => 0,
            "pointsScored" => 0,
            "pointsMissed" => 0,
            "plusMinus" => 0,
        );
        foreach ($team->getStats() as $stat) {
            $statistic['gamePlayed'] +=  $stat->getGamesPlayed();
            $statistic['gameWon'] +=  $stat->getGamesWin();
            $statistic['pointsScored'] +=  $stat->getPointsScored();
            $statistic['pointsMissed'] +=  $stat->getPointsMissed();
        }
        if($statistic['gamePlayed']>0)
            $statistic['plusMinus'] = ($statistic['pointsScored']-$statistic['pointsMissed'])/$statistic['gamePlayed'];
        return $statistic;
    }

}