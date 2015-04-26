<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegisteredReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegisteredReservationRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBulder()
    {
        return $this->createQueryBuilder("r");
    }
    /**
     * @param string $date
     * @return array
     */
    public function findOlderThenData($date){
        $r = $this->getQueryBulder()
            ->select()
            ->where('r.reservationEnd < :date')
            ->setParameter('date', $date)
            ->getQuery()->getResult();
        return $r;
    }
}
