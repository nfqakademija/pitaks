<?php
namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventTableRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBulder()
    {
        return $this->createQueryBuilder("et");
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function getEvetsTableList(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        if (empty($criteria)) {
            return $this->findAll();
        }

        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param EventTable $event
     */
    public function save(EventTable $event)
    {
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
    }


    /**
     * @param $tableId
     * @return int
     */
    public function getLastEvent($tableId)
    {
        /*SELECT MAX(id) FROM tablename*/
        $query= $this->getQueryBulder()
            ->select('MAX(et.id)')
            ->where('et.table_id='.$tableId)
        ->getQuery();
        //return max id
        return intval($query->getResult()[0][1]);
    }
}