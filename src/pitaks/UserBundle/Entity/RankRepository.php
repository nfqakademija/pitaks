<?php

namespace pitaks\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RankRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RankRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBulder()
    {
        return $this->createQueryBuilder("ra");
    }

    /**
     * @return array
     */
    public function getOrderedRanks()
    {
       $bulder= $this->getQueryBulder();
        $query = $bulder
            ->select('ra')
            ->orderBy('ra.win')
            ->addOrderBy('ra.scored')
            ->getQuery();
        return $query->getResult();
    }
}
