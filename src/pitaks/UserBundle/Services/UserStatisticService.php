<?php
namespace pitaks\UserBundle\Services;
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 17:03
 */
use Doctrine\ORM\EntityManager;
use pitaks\KickerBundle\Entity\Game;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\UserBundle\Entity\User;
use pitaks\UserBundle\Entity\UserTableStatistic;
use Symfony\Component\DependencyInjection\ContainerAware;

class UserStatisticService extends ContainerAware {

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
    public function addUserStatistic($game)
    {
        $card11 = $game->getUser1Team1();
        $card12 = $game->getUser2Team1();
        $card21 = $game->getUser1Team2();
        $card22 = $game->getUser2Team2();
        $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->find($game->getTableId());
        if($card11 || $card12 || $card21 || $card22)
        {
            /*if exits do something cool*/
            if($card11!=null)
            {
                $user = $this->getEm()->getRepository('UserBundle:User')->findOneBy(array('cardId'=>$card11));
                if($user) {
                    $this->setUserData($game, 0,$user,$table);
                }
            }
            if($card12!=null)
            {
                $user = $this->getEm()->getRepository('UserBundle:User')->findOneBy(array('cardId'=>$card12));
                if($user) {
                    $this->setUserData($game, 0,$user,$table);
                }
            }
            if($card21!=null)
            {
                $user = $this->getEm()->getRepository('UserBundle:User')->findOneBy(array('cardId'=>$card21));
                if($user) {
                    $this->setUserData($game, 1,$user,$table);
                }
            }
            if($card22!=null)
            {
                $user = $this->getEm()->getRepository('UserBundle:User')->findOneBy(array('cardId'=>$card22));
                if($user) {
                    $this->setUserData($game, 1,$user,$table);
                }
            }
        }
    }

    //user team can be 1 or 0 0-first 1-second
    /**
     * @param Game $game
     * @param integer $userTeam
     * @param User $user
     * @param Tables $table
     */


    public function setUserData($game, $userTeam,$user,$table)
    {
        /*pasiziuret ar turi data ar ne jei ne tj sukurt ir idet su nulais*/
        $userStatistic = $this->getEm()->getRepository('UserBundle:UserTableStatistic')
            ->findOneBy(array('userId' => $user->getId(), 'tableId' => $table->getId()));
        /*Create new statistic for user*/
        if($userStatistic == null){
            $userStatistic = new UserTableStatistic();
            $userStatistic->setUserId($user);
            $userStatistic->setTableId($table);
            $this->getEm()->persist($userStatistic);
            $this->getEm()->flush();
        }
        $userStatistic->increaseUserGamesCount();
        //if user belong to the first team
        if($userTeam == 0) {
            if ($game->getScoreTeam1() > $game->getScoreTeam2()) {
                $userStatistic->increaseUserWinGameCount();
            }
            $userStatistic->setPointsScored($game->getScoreTeam1()+$userStatistic->getPointsScored());
            $userStatistic->setPointsMissed($game->getScoreTeam2()+$userStatistic->getPointsMissed());
            $this->getEm()->flush();
        }
        else
        {
            if ($game->getScoreTeam1() < $game->getScoreTeam2()) {
                $userStatistic->increaseUserWinGameCount();
            }
            $userStatistic->setPointsScored($game->getScoreTeam2()+$userStatistic->getPointsScored());
            $userStatistic->setPointsMissed($game->getScoreTeam1()+$userStatistic->getPointsMissed());
            $this->getEm()->flush();
        }

    }
}