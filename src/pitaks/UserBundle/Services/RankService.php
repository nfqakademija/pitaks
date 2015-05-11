<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.10
 * Time: 23:11
 */
namespace pitaks\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use pitaks\KickerBundle\Event\OnRankChangeEvent;
use pitaks\UserBundle\Entity\Rank;
use pitaks\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class RankService extends ContainerAware{

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
     * @param User $user
     */
    public function setUserRank($user)
    {
        $allStatistic = $this->container->get('user_statistic_service')->returnAllUserStatistic($user);
        $rank = $user->getRank();
        $dispatcher = $this->container->get('event_dispatcher');
        if($rank)
        {
            if($allStatistic->getGamesWon()>$rank->getWin() && $allStatistic->getPointsScored()>$rank->getScored())
            {
                $ranks = $this->em->getRepository('UserBundle:Rank')->findAll();
                foreach($ranks as $newRank)
                {
                    if($rank->getWin()<$newRank->getWin() && $newRank->getWin()<= $allStatistic->getGamesWon() &&
                    $rank->getScored() < $newRank->getScored() && $newRank->getScored()<=$allStatistic->getPointsScored())
                    {
                        $user->setRank($newRank);
                        $this->em->flush();
                    }
                }
                if($rank != $user->getRank())
                {
                    $this->container->get('user_lastviews_service')->updateRankDate($user);
                    $this->em->flush();
                }
            }
        }
        else {
            $newRank = $this->em->getRepository('UserBundle:Rank')->findOneBy(array('win' => 0, 'scored' => 0));
            if($newRank)
            {
                $rank = new Rank();
                $rank->setName('begemotas');
                $rank->setWin(0);
                $rank->setScored(0);
                $this->em->persist($rank);
                $user->setRank($rank);
                $this->em->flush();
            }
        }
    }


}