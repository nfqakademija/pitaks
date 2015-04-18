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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationStart", type="datetime")
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
     * @ORM\Column(name="reservatioDuration", type="integer",nullable=true)
     */
    private $reservatioDuration;

    /**
     *
     *
     * @ORM\ManyToOne(targetEntity="Tables", inversedBy="reservations")
     * @ORM\JoinColumn(name="tableId", referencedColumnName="id")
     */
    private $tableId;

    /**
     * @var string
     *
     * @ORM\Column(name="userId", type="string", length=255)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="friendId", type="string", length=255, nullable=true)
     */
    private $friendId;

    /**
     * @var boolean
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Reservation
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
     * Set reservatioDuration
     *
     * @param integer $reservatioDuration
     * @return Reservation
     */
    public function setReservatioDuration($reservatioDuration)
    {
        $this->reservatioDuration = $reservatioDuration;

        return $this;
    }

    /**
     * Get reservatioDuration
     *
     * @return integer 
     */
    public function getReservatioDuration()
    {
        return $this->reservatioDuration;
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
     * Set userId
     *
     * @param string $userId
     * @return Reservation
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }
    public function getReservationStartHour()
    {
        return $this->reservationStart->format("H:i");
    }

    /**
     * Set friendId
     *
     * @param string $friendId
     * @return Reservation
     */
    public function setFriendId($friendId)
    {
        $this->friendId = $friendId;

        return $this;
    }

    /**
     * Get friendId
     *
     * @return string 
     */
    public function getFriendId()
    {
        return $this->friendId;
    }



    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Reservation
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }
}
