<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\EntityRepository;
use pitaks\UserBundle\Entity\User;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
    public function getQueryBulder()
    {
        return $this->createQueryBuilder("g");
    }

    /**
     * @param $tableId
     * @return mixed
     */
    public function getLastGame($tableId){
        $query= $this->getQueryBulder()
            ->select('g')
            ->where('g.tableId='.$tableId)
            ->orderBy('g.id','DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);
        //return last Game as array?
        if($query->getQuery()->getResult())
        return $query->getQuery()->getResult()[0];
        return null;
    }

    /**
     * @param $tableId
     * @return mixed
     */
    public function getLastPlayedGameId($tableId)
    {
        /*SELECT MAX(id) FROM tablename*/
        $query= $this->getQueryBulder()
            ->select('MAX(g.id)')
            ->where('g.tableId='.$tableId)
            ->getQuery();
        //return last Game as array?
        return $query->getResult()[0];
    }


    /**
     * @param User $user
     * @param int $n
     * @return mixed
     */
    public function getLastNUserGames($user,$n){

        $query= $this->getQueryBulder()
            ->select('g')
            ->where('g.user1Team1='.$user->getCardId())
            ->orWhere('g.user2Team1='.$user->getCardId())
            ->orWhere('g.user1Team2='.$user->getCardId())
            ->orWhere('g.user2Team2='.$user->getCardId())
            ->orderBy('g.beginTime','DESC')
            ->setFirstResult(0)
            ->setMaxResults($n);
            return $query->getQuery()->getResult();
    }

    /**
     * @param $tableId
     * @return \Doctrine\ORM\Query
     */
    public function getGamesForTableWhereResult($tableId)
    {
        return $this->getQueryBulder()
            ->select('g')
            ->where('g.scoreTeam1 > :number1')
            ->andWhere('g.scoreTeam2 > :number2')
            ->andWhere('g.tableId='.$tableId)
            ->setParameter('number1', '0')
            ->setParameter('number2', '0')
            ->orderBy('g.beginTime','DESC')
            ->getQuery()->getResult();

    }

}
