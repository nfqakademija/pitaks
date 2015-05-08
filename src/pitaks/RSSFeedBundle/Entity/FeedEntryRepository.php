<?php

namespace pitaks\RSSFeedBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FeedEntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FeedEntryRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBulder()
    {
        return $this->createQueryBuilder("a");
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function feedsQuery(){
        $query = $this->getQueryBulder()
            ->select()
            ->orderBy('a.date',"DESC")
            ->getQuery();
        return $query;
    }
}
