<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RegisteredReservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\KickerBundle\Entity\RegisteredReservationRepository")
 */
class RegisteredReservation
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
     * @ORM\Column(name="userId", type="integer",nullable=true)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="friendId", type="integer",nullable=true)
     */
    private $friendId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isConfirmed", type="boolean")
     */
    private $isConfirmed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime",nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="registeredReservationId")
     */
    protected $reservations;

    function __construct()
    {
        $this->reservations = new ArrayCollection();
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
     * Set userId
     *
     * @param integer $userId
     * @return RegisteredReservation
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set friendId
     *
     * @param integer $friendId
     * @return RegisteredReservation
     */
    public function setFriendId($friendId)
    {
        $this->friendId = $friendId;

        return $this;
    }

    /**
     * Get friendId
     *
     * @return integer 
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     * @return RegisteredReservation
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean 
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return RegisteredReservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add reservations
     *
     * @param \pitaks\KickerBundle\Entity\Reservation $reservations
     * @return RegisteredReservation
     */
    public function addReservation(\pitaks\KickerBundle\Entity\Reservation $reservations)
    {
        $this->reservations[] = $reservations;

        return $this;
    }

    /**
     * Remove reservations
     *
     * @param \pitaks\KickerBundle\Entity\Reservation $reservations
     */
    public function removeReservation(\pitaks\KickerBundle\Entity\Reservation $reservations)
    {
        $this->reservations->removeElement($reservations);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Set reservationStart
     *
     * @param \DateTime $reservationStart
     * @return RegisteredReservation
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
     * @return RegisteredReservation
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
}
