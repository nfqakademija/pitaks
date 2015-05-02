<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\KickerBundle\Entity\ReservationRepository")
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationStart", type="datetime",nullable=true)
     */
    private $reservationStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationEnd", type="datetime",nullable=true)
     */
    private $reservationEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="reservationDuration", type="integer",nullable=true)
     */
    private $reservationDuration;

    /**
     * @ORM\ManyToOne(targetEntity="Tables", inversedBy="reservations")
     * @ORM\JoinColumn(name="tableId", referencedColumnName="id")
     */
    private $tableId;

    /**
     * @ORM\ManyToOne(targetEntity="RegisteredReservation", inversedBy="reservations")
     * @ORM\JoinColumn(name="registeredReservationd", referencedColumnName="id")
     */
    private $registeredReservationId;

    /**
     * @ORM\ManyToOne(targetEntity="pitaks\TeamBundle\Entity\TeamReservation", inversedBy="reservations")
     * @ORM\JoinColumn(name="teamReservation", referencedColumnName="id")
     */
    private $teamReservation;

    /**
     * @var boolean
     * @ORM\Column(name="isFree", type="boolean" ,nullable=true)
     */
    private $isFree;

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
     * Set reservationStart
     *
     * @param \DateTime $reservationStart
     * @return Reservation
     */
    public function setReservationStart($reservationStart)
    {
        $this->reservationStart = $reservationStart;

        return $this;
    }

    /**
     * Get reservationStart
     *
     * @return \DateTime 
     */
    public function getReservationStart()
    {
        return $this->reservationStart;
    }

    /**
     * Set reservationEnd
     *
     * @param \DateTime $reservationEnd
     * @return Reservation
     */
    public function setReservationEnd($reservationEnd)
    {
        $this->reservationEnd = $reservationEnd;

        return $this;
    }

    /**
     * Get reservationEnd
     *
     * @return \DateTime 
     */
    public function getReservationEnd()
    {
        return $this->reservationEnd;
    }

    /**
     * Set reservationDuration
     *
     * @param integer $reservationDuration
     * @return Reservation
     */
    public function setReservationDuration($reservationDuration)
    {
        $this->reservationDuration = $reservationDuration;

        return $this;
    }

    /**
     * Get reservationDuration
     *
     * @return integer 
     */
    public function getReservationDuration()
    {
        return $this->reservationDuration;
    }

    /**
     * Set tableId
     *
     * @param integer $tableId
     * @return Reservation
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;

        return $this;
    }

    /**
     * Get tableId
     *
     * @return integer 
     */
    public function getTableId()
    {
        return $this->tableId;
    }
    
    /**
     * @return string
     */
    public function getReservationStartHour()
    {
        return $this->reservationStart->format("H:i");
    }

    public function getReservationEndHour(){
        return $this->reservationEnd->format("H:i");
    }


    /**
     * Set isFree
     *
     * @param boolean $isFree
     * @return Reservation
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return boolean 
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set registeredReservationId
     *
     * @param \pitaks\KickerBundle\Entity\RegisteredReservation $registeredReservationId
     * @return Reservation
     */
    public function setRegisteredReservationId(\pitaks\KickerBundle\Entity\RegisteredReservation $registeredReservationId = null)
    {
        $this->registeredReservationId = $registeredReservationId;

        return $this;
    }

    /**
     * Get registeredReservationId
     *
     * @return \pitaks\KickerBundle\Entity\RegisteredReservation 
     */
    public function getRegisteredReservationId()
    {
        return $this->registeredReservationId;
    }

    /**
     * Set teamReservation
     *
     * @param \pitaks\TeamBundle\Entity\TeamReservation $teamReservation
     * @return Reservation
     */
    public function setTeamReservation(\pitaks\TeamBundle\Entity\TeamReservation $teamReservation = null)
    {
        $this->teamReservation = $teamReservation;

        return $this;
    }

    /**
     * Get teamReservation
     *
     * @return \pitaks\TeamBundle\Entity\TeamReservation
     */
    public function getTeamReservation()
    {
        return $this->teamReservation;
    }
}
