<?php

namespace pitaks\KickerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\KickerBundle\Entity\GameRepository")
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="Tables", inversedBy="games")
     *@ORM\JoinColumn(name="tableId", referencedColumnName="id")
     */
    private $tableId;

    /**
     * @var integer
     *
     * @ORM\Column(name="startEventId", type="integer")
     */
    private $startEventId;

    /**
     * @var integer
     *
     * @ORM\Column(name="endEventId", type="integer",nullable=true)
     */
    private $endEventId;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastAddedEventId", type="integer")
     */
    private $lastAddedEventId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user1Team1", type="integer", nullable=true)
     */
    private $user1Team1;

    /**
     * @var integer
     *
     * @ORM\Column(name="user2Team2", type="integer", nullable=true)
     */
    private $user2Team2;

    /**
     * @var integer
     *
     * @ORM\Column(name="user1Team2", type="integer", nullable=true)
     */
    private $user1Team2;

    /**
     * @var integer
     *
     * @ORM\Column(name="user2Team1", type="integer", nullable=true)
     */
    private $user2Team1;

    /**
     * @var integer
     *
     * @ORM\Column(name="scoreTeam1", type="integer")
     */
    private $scoreTeam1;

    /**
     * @var integer
     *
     * @ORM\Column(name="scoreTeam2", type="integer", length=255)
     */
    private $scoreTeam2;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastTime", type="integer")
     */
    private $lastTime;



    /**
     * @var integer
     *
     * @ORM\Column(name="beginTime", type="integer")
     */
    private $beginTime;
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
     * Set tableId
     *
     * @param integer $tableId
     * @return Game
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
     * Set startEventId
     *
     * @param integer $startEventId
     * @return Game
     */
    public function setStartEventId($startEventId)
    {
        $this->startEventId = $startEventId;

        return $this;
    }

    /**
     * Get startEventId
     *
     * @return integer 
     */
    public function getStartEventId()
    {
        return $this->startEventId;
    }

    /**
     * Set endEventId
     *
     * @param integer $endEventId
     * @return Game
     */
    public function setEndEventId($endEventId)
    {
        $this->endEventId = $endEventId;

        return $this;
    }

    /**
     * Get endEventId
     *
     * @return integer 
     */
    public function getEndEventId()
    {
        return $this->endEventId;
    }

    /**
     * Set lastAddedEventId
     *
     * @param integer $lastAddedEventId
     * @return Game
     */
    public function setLastAddedEventId($lastAddedEventId)
    {
        $this->lastAddedEventId = $lastAddedEventId;

        return $this;
    }

    /**
     * Get lastAddedEventId
     *
     * @return integer 
     */
    public function getLastAddedEventId()
    {
        return $this->lastAddedEventId;
    }

    /**
     * Set user1Team1
     *
     * @param integer $user1Team1
     * @return Game
     */
    public function setUser1Team1($user1Team1)
    {
        $this->user1Team1 = $user1Team1;

        return $this;
    }

    /**
     * Get user1Team1
     *
     * @return integer 
     */
    public function getUser1Team1()
    {
        return $this->user1Team1;
    }

    /**
     * Set user2Team2
     *
     * @param integer $user2Team2
     * @return Game
     */
    public function setUser2Team2($user2Team2)
    {
        $this->user2Team2 = $user2Team2;

        return $this;
    }

    /**
     * Get user2Team2
     *
     * @return integer 
     */
    public function getUser2Team2()
    {
        return $this->user2Team2;
    }

    /**
     * Set user1Team2
     *
     * @param integer $user1Team2
     * @return Game
     */
    public function setUser1Team2($user1Team2)
    {
        $this->user1Team2 = $user1Team2;

        return $this;
    }

    /**
     * Get user1Team2
     *
     * @return integer 
     */
    public function getUser1Team2()
    {
        return $this->user1Team2;
    }

    /**
     * Set user2Team1
     *
     * @param integer $user2Team1
     * @return Game
     */
    public function setUser2Team1($user2Team1)
    {
        $this->user2Team1 = $user2Team1;

        return $this;
    }

    /**
     * Get user2Team1
     *
     * @return integer 
     */
    public function getUser2Team1()
    {
        return $this->user2Team1;
    }

    /**
     * Set scoreTeam1
     *
     * @param integer $scoreTeam1
     * @return Game
     */
    public function setScoreTeam1($scoreTeam1)
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }

    /**
     * Get scoreTeam1
     *
     * @return integer 
     */
    public function getScoreTeam1()
    {
        return $this->scoreTeam1;
    }

    /**
     * Set scoreTeam2
     *
     * @param integer $scoreTeam2
     * @return Game
     */
    public function setScoreTeam2($scoreTeam2)
    {
        $this->scoreTeam2 = $scoreTeam2;

        return $this;
    }

    /**
     * Get scoreteam2Team2
     *
     * @return integer
     */
    public function getScoreTeam2()
    {
        return $this->scoreTeam2;
    }

    /**
     * Set lastTime
     *
     * @param integer $lastTime
     * @return Game
     */
    public function setLastTime($lastTime)
    {
        $this->lastTime = $lastTime;

        return $this;
    }

    /**
     * Get lastTime
     *
     * @return integer 
     */
    public function getLastTime()
    {
        return $this->lastTime;
    }

    /**
     * Set beginTime
     *
     * @param integer $beginTime
     * @return Game
     */
    public function setBeginTime($beginTime)
    {
        $this->beginTime = $beginTime;

        return $this;
    }

    /**
     * Get beginTime
     *
     * @return integer 
     */
    public function getBeginTime()
    {
        return $this->beginTime;
    }
}
