<?php

namespace pitaks\TeamBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TeamReservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\TeamBundle\Entity\TeamReservationRepository")
 */
class TeamReservation
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
     * @ORM\ManyToOne(targetEntity="\pitaks\TeamBundle\Entity\Team", inversedBy="reservations")
     * @ORM\JoinColumn(name="team", referencedColumnName="id")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="\pitaks\TeamBundle\Entity\Team", inversedBy="invitedReservations")
     * @ORM\JoinColumn(name="competitorTeam", referencedColumnName="id")
     */
    private $competitorTeam;

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
     * @ORM\OneToMany(targetEntity="pitaks\KickerBundle\Entity\Reservation", mappedBy="teamReservation")
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
     * Set reservationStart
     *
     * @param \DateTime $reservationStart
     * @return TeamReservation
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
     * @return TeamReservation
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
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     * @return TeamReservation
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
     * @return TeamReservation
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
     * Set team
     *
     * @param \pitaks\TeamBundle\Entity\Team $team
     * @return TeamReservation
     */
    public function setTeam( $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \pitaks\TeamBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set competitorTeam
     *
     * @param Team $competitorTeam
     * @return TeamReservation
     */
    public function setCompetitorTeam(Team $competitorTeam = null)
    {
        $this->competitorTeam = $competitorTeam;

        return $this;
    }

    /**
     * Get competitorTeam
     *
     * @return \pitaks\TeamBundle\Entity\Team 
     */
    public function getCompetitorTeam()
    {
        return $this->competitorTeam;
    }

    /**
     * Add reservations
     *
     * @param \pitaks\KickerBundle\Entity\Reservation $reservations
     * @return TeamReservation
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
}
