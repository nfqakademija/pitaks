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

    /**
     * @var string
     * @ORM\Column(name="address", type="string")
     */
    protected $address;

    /**
     * @ORM\OneToMany(targetEntity="TableRate", mappedBy="tableId")
     */
    protected $ratings;

    /**
     * @ORM\ManyToMany(targetEntity="pitaks\CommentsBundle\Entity\Comment", inversedBy="tableId")
     * @ORM\JoinTable(name="comments")
     **/
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\UserBundle\Entity\UserTableStatistic", mappedBy="tableId")
     */
    protected $tableUsersStatistic;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\TeamBundle\Entity\TeamStatistic", mappedBy="table")
     */
    protected $tableTeamStatistic;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tableUsersStatistic = new ArrayCollection();
        $this->tableTeamStatistic = new ArrayCollection();
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

    /**
     * Set address
     *
     * @param string $address
     * @return Tables
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add ratings
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $ratings
     * @return Tables
     */
    public function addRating(\pitaks\KickerBundle\Entity\TableRate $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $ratings
     */
    public function removeRating(\pitaks\KickerBundle\Entity\TableRate $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @return float
     */
    public function getTableRate()
    {
        $n= $this->ratings->count();
        $sum = 0;
        if($n > 0){
        foreach($this->ratings as $rate) {
            $sum += $rate->getRating();
        }
        return $sum/$n;}
        return 0;
    }

    /**
     * Add tableUsersStatistic
     *
     * @param \\pitaks\UserBundle\Entity\UserTableStatistic $tableUsersStatistic
     * @return Tables
     */
    public function addTableUsersStatistic(\pitaks\UserBundle\Entity\UserTableStatistic $tableUsersStatistic)
    {
        $this->tableUsersStatistic[] = $tableUsersStatistic;

        return $this;
    }

    /**
     * Remove tableUsersStatistic
     *
     * @param \pitaks\UserBundle\Entity\UserTableStatistic $tableUsersStatistic
     */
    public function removeTableUsersStatistic(\pitaks\UserBundle\Entity\UserTableStatistic $tableUsersStatistic)
    {
        $this->tableUsersStatistic->removeElement($tableUsersStatistic);
    }

    /**
     * Get tableUsersStatistic
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTableUsersStatistic()
    {
        return $this->tableUsersStatistic;
    }

    /**
     * Add comments
     *
     * @param \pitaks\CommentsBundle\Entity\Comment $comments
     * @return Tables
     */
    public function addComment(\pitaks\CommentsBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \pitaks\CommentsBundle\Entity\Comment $comments
     */
    public function removeComment(\pitaks\CommentsBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add tableTeamStatistic
     *
     * @param \pitaks\TeamBundle\Entity\TeamStatistic $tableTeamStatistic
     * @return Tables
     */
    public function addTableTeamStatistic(\pitaks\TeamBundle\Entity\TeamStatistic $tableTeamStatistic)
    {
        $this->tableTeamStatistic[] = $tableTeamStatistic;

        return $this;
    }

    /**
     * Remove tableTeamStatistic
     *
     * @param \pitaks\TeamBundle\Entity\TeamStatistic $tableTeamStatistic
     */
    public function removeTableTeamStatistic(\pitaks\TeamBundle\Entity\TeamStatistic $tableTeamStatistic)
    {
        $this->tableTeamStatistic->removeElement($tableTeamStatistic);
    }

    /**
     * Get tableTeamStatistic
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTableTeamStatistic()
    {
        return $this->tableTeamStatistic;
    }
}
