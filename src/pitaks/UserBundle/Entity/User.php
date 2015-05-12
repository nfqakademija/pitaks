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
use pitaks\UserBundle\Entity\LastReviews;
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
     * @ORM\ManyToOne(targetEntity="Rank", inversedBy="users")
     * @ORM\JoinColumn(name="rank", referencedColumnName="id")
     */
    protected $rank;
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
     * @ORM\OneToMany(targetEntity="UserTableStatistic", mappedBy="userId")
     */
    protected $userTablesStatistic;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\CommentsBundle\Entity\Comment", mappedBy="userId")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="\pitaks\TeamBundle\Entity\Team", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     **/
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="\pitaks\TeamBundle\Entity\Team", mappedBy="author")
     */
    private $createdTeams;

    /**
     * @ORM\OneToOne(targetEntity="LastReviews", cascade={"persist"})
     * @ORM\JoinColumn(name="reviewsId", referencedColumnName="id")
     **/
    private $reviews;

    /**
     * @ORM\OneToOne(targetEntity="\pitaks\KickerBundle\Entity\Document")
     * @ORM\JoinColumn(name="imageId", referencedColumnName="id")
     **/
    private $image;

    public function __construct()
    {
        parent::__construct();
        $this->tablesRating= new ArrayCollection();
        $this->userTablesStatistic= new ArrayCollection();
        $this->teams= new ArrayCollection();
        $this->createdTeams= new ArrayCollection();
        //sukuriam reviews pirmas
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
     * @param \pitaks\CommentsBundle\Entity\Comment $comments
     * @return User
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
     * Add teams
     *
     * @param \pitaks\TeamBundle\Entity\Team $teams
     * @return User
     */
    public function addTeam(\pitaks\TeamBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \pitaks\TeamBundle\Entity\Team $teams
     */
    public function removeTeam(\pitaks\TeamBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Add createdTeams
     *
     * @param \pitaks\TeamBundle\Entity\Team $createdTeams
     * @return User
     */
    public function addCreatedTeam(\pitaks\TeamBundle\Entity\Team $createdTeams)
    {
        $this->createdTeams[] = $createdTeams;

        return $this;
    }

    /**
     * Remove createdTeams
     *
     * @param \pitaks\TeamBundle\Entity\Team $createdTeams
     */
    public function removeCreatedTeam(\pitaks\TeamBundle\Entity\Team $createdTeams)
    {
        $this->createdTeams->removeElement($createdTeams);
    }

    /**
     * Get createdTeams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCreatedTeams()
    {
        return $this->createdTeams;
    }

    /**
     * Set reviews
     *
     * @param \pitaks\UserBundle\Entity\LastReviews $reviews
     * @return User
     */
    public function setReviews(\pitaks\UserBundle\Entity\LastReviews $reviews = null)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Get reviews
     *
     * @return \pitaks\UserBundle\Entity\LastReviews 
     */
    public function getReviews()
    {
        return $this->reviews;
    }

 



    /**
     * Set image
     *
     * @param \pitaks\KickerBundle\Entity\Document $image
     * @return User
     */
    public function setImage(\pitaks\KickerBundle\Entity\Document $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \pitaks\KickerBundle\Entity\Document 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set rank
     *
     * @param \pitaks\UserBundle\Entity\Rank $rank
     * @return User
     */
    public function setRank(\pitaks\UserBundle\Entity\Rank $rank = null)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return \pitaks\UserBundle\Entity\Rank 
     */
    public function getRank()
    {
        return $this->rank;
    }
}
