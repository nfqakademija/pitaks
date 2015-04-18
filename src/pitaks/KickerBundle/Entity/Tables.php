<?php

namespace pitaks\KickerBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tables
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\KickerBundle\Entity\TablesRepository")
 */
class Tables
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60)
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @var string
     *  @Assert\Url()
     * @ORM\Column(name="api_url", type="string", length=255)
     *
     */
    private $apiUrl;

    /**
     * @var string
     * @Assert\Length(min=3)
     * @ORM\Column(name="user_Name", type="string", length=255)
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(min=5)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="tableId")
     */
    protected $reservations;

    /**
    * @ORM\OneToMany(targetEntity="EventTable", mappedBy="table_id")
     *
    */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="tableId")
     */
    protected $games;

    /**
     * @var bool
     * @ORM\Column(name="isFree", type="boolean")
     */
    protected $isFree;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->games = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Tables
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set apiUrl
     *
     * @param string $apiUrl
     * @return Tables
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Get apiUrl
     *
     * @return string 
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return Tables
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Tables
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add reservations
     *
     * @param \pitaks\KickerBundle\Entity\Reservation $reservations
     * @return Tables
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
     * Add events
     *
     * @param \pitaks\KickerBundle\Entity\EventTable $events
     * @return Tables
     */
    public function addEvent(\pitaks\KickerBundle\Entity\EventTable $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \pitaks\KickerBundle\Entity\EventTable $events
     */
    public function removeEvent(\pitaks\KickerBundle\Entity\EventTable $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set isFree
     *
     * @param boolean $isFree
     * @return Tables
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
     * Add games
     *
     * @param \pitaks\KickerBundle\Entity\Game $games
     * @return Tables
     */
    public function addGame(\pitaks\KickerBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \pitaks\KickerBundle\Entity\Game $games
     */
    public function removeGame(\pitaks\KickerBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }
}
