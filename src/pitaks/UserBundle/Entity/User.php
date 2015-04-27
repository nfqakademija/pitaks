<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.22
 * Time: 23:25
 */
namespace pitaks\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var integer
     *
     * @ORM\Column(name="cardId", type="integer",nullable=true)
     */
    protected $cardId;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=60,nullable=true)
     *
     */
    protected $name;
    
    /**
     * @var string
     * @ORM\Column(name="lastName", type="string", length=60,nullable=true)
     *
     */
    protected $lastName;


    /**
     * @var integer
     *
     * @ORM\Column(name="gamesPlayed", type="integer",nullable=true)
     */
    protected $gamesPlayed;
    /**
     * @var integer
     *
     * @ORM\Column(name="gamesWin", type="integer",nullable=true)
     */
    protected $gamesWin;
    /**
     * @var integer
     *
     * @ORM\Column(name="pointScored", type="integer",nullable=true)
     */
    protected $pointsScored;
    /**
     * @var integer
     *
     * @ORM\Column(name="pointsMissed", type="integer",nullable=true)
     */
    protected $pointsMissed;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\KickerBundle\Entity\TableRate", mappedBy="username")
     */
    protected $tablesRating;

    /**
     * @ORM\OneToMany(targetEntity="UserTableStatistic", mappedBy="username")
     */
    protected $userTablesStatistic;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\KickerBundle\Entity\Comment", mappedBy="tableId")
     */
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        $this->tablesRating= new ArrayCollection();
        $this->userTablesStatistic= new ArrayCollection();
    }



    /**
     * @return mixed
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * @param mixed $cardId
     */
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;
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
     * @return User
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
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add tablesRating
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $tablesRating
     * @return User
     */
    public function addTablesRating(\pitaks\KickerBundle\Entity\TableRate $tablesRating)
    {
        $this->tablesRating[] = $tablesRating;

        return $this;
    }

    /**
     * Remove tablesRating
     *
     * @param \pitaks\KickerBundle\Entity\TableRate $tablesRating
     */
    public function removeTablesRating(\pitaks\KickerBundle\Entity\TableRate $tablesRating)
    {
        $this->tablesRating->removeElement($tablesRating);
    }

    /**
     * Get tablesRating
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTablesRating()
    {
        return $this->tablesRating;
    }

    /**
     * Set gamesPlayed
     *
     * @param integer $gamesPlayed
     * @return User
     */
    public function setGamesPlayed($gamesPlayed)
    {
        $this->gamesPlayed = $gamesPlayed;

        return $this;
    }

    /**
     * Get gamesPlayed
     *
     * @return integer 
     */
    public function getGamesPlayed()
    {
        return $this->gamesPlayed;
    }

    /**
     * Set gamesWin
     *
     * @param integer $gamesWin
     * @return User
     */
    public function setGamesWin($gamesWin)
    {
        $this->gamesWin = $gamesWin;

        return $this;
    }

    /**
     * Get gamesWin
     *
     * @return integer 
     */
    public function getGamesWin()
    {
        return $this->gamesWin;
    }

    /**
     * Set pointsScored
     *
     * @param integer $pointsScored
     * @return User
     */
    public function setPointsScored($pointsScored)
    {
        $this->pointsScored = $pointsScored;

        return $this;
    }

    /**
     * Get pointsScored
     *
     * @return integer 
     */
    public function getPointsScored()
    {
        return $this->pointsScored;
    }

    /**
     * Set pointsMissed
     *
     * @param integer $pointsMissed
     * @return User
     */
    public function setPointsMissed($pointsMissed)
    {
        $this->pointsMissed = $pointsMissed;

        return $this;
    }

    /**
     * Get pointsMissed
     *
     * @return integer 
     */
    public function getPointsMissed()
    {
        return $this->pointsMissed;
    }



  /**
     * Add userTablesStatistic
     *
     * @param \pitaks\UserBundle\Entity\UserTableStatistic $userTablesStatistic
     * @return User
     */
    public function addUserTablesStatistic(\pitaks\UserBundle\Entity\UserTableStatistic $userTablesStatistic)
    {
        $this->userTablesStatistic[] = $userTablesStatistic;

        return $this;
    }

    /**
     * Remove userTablesStatistic
     *
     * @param \pitaks\UserBundle\Entity\UserTableStatistic $userTablesStatistic
     */
    public function removeUserTablesStatistic(\pitaks\UserBundle\Entity\UserTableStatistic $userTablesStatistic)
    {
        $this->userTablesStatistic->removeElement($userTablesStatistic);
    }

    /**
     * Get userTablesStatistic
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserTablesStatistic()
    {
        return $this->userTablesStatistic;
    }

    /**
     * Add comments
     *
     * @param \pitaks\KickerBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\pitaks\KickerBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \pitaks\KickerBundle\Entity\Comment $comments
     */
    public function removeComment(\pitaks\KickerBundle\Entity\Comment $comments)
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
}
