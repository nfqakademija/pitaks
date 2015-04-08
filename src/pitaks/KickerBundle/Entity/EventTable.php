<?php

namespace pitaks\KickerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="eventTable")
 *
 * @ORM\Entity(repositoryClass="pitaks\KickerBundle\Entity\EventTableRepository")
 */

class EventTable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    protected $id;
    /**
     * @ORM\Column(type="integer")
     */
    protected $timeSec;
    /**
     * @ORM\Column(type="integer")
     */
    protected $usec;
    /**
     * @ORM\Column(type="string")
     */
    protected $type;
    /**
     * @ORM\Column(type="string")
     */
    protected $data;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Tables", inversedBy="events")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id")
     */
    protected $table_id;

    function __construct(){
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return EventTable
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set timeSec
     *
     * @param integer $timeSec
     * @return EventTable
     */
    public function setTimeSec($timeSec)
    {
        $this->timeSec = $timeSec;

        return $this;
    }

    /**
     * Get timeSec
     *
     * @return integer 
     */
    public function getTimeSec()
    {
        return $this->timeSec;
    }

    /**
     * Set usec
     *
     * @param integer $usec
     * @return EventTable
     */
    public function setUsec($usec)
    {
        $this->usec = $usec;

        return $this;
    }

    /**
     * Get usec
     *
     * @return integer 
     */
    public function getUsec()
    {
        return $this->usec;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return EventTable
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return EventTable
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set table_id
     *
     * @param integer $tableId
     * @return EventTable
     */
    public function setTableId($tableId)
    {
        $this->table_id = $tableId;

        return $this;
    }

    /**
     * Get table_id
     *
     * @return integer 
     */
    public function getTableId()
    {
        return $this->table_id;
    }
}
