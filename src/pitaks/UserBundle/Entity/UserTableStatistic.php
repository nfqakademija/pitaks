<?php

namespace pitaks\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;

/**
 * UserTableStatistic
 *
 * @ORM\Table( uniqueConstraints={@UniqueConstraint(name="rating_unique", columns={"tableId", "userId"})})
 * @ORM\Entity(repositoryClass="pitaks\UserBundle\Entity\UserTableStatisticRepository")
 */
class UserTableStatistic
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
     * @var integer
     *
     * @ORM\Column(name="gamesPlayed", type="integer")
     */
    private $gamesPlayed;

    /**
     * @var integer
     *
     * @ORM\Column(name="gamesWin", type="integer")
     */
    private $gamesWin;

    /**
     * @var integer
     *
     * @ORM\Column(name="pointsScored", type="integer")
     */
    private $pointsScored;

    /**
     * @var integer
     *
     * @ORM\Column(name="pointsMissed", type="integer")
     */
    private $pointsMissed;

    /**
     * @ORM\ManyToOne(targetEntity="\pitaks\KickerBundle\Entity\Tables", inversedBy="tableUsersStatistic")
     * @ORM\JoinColumn(name="tableId", referencedColumnName="id")
     */
    private $tableId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTablesStatistic")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    function __construct()
    {
        $this->gamesPlayed = 0;
        $this->gamesWin = 0;
        $this->pointsScored = 0;
        $this->pointsMissed = 0;
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
     * Set gamesPlayed
     *
     * @param integer $gamesPlayed
     * @return UserTableStatistic
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
     * @return UserTableStatistic
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
     * @return UserTableStatistic
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
     * @return UserTableStatistic
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
     * Set tableId
     *
     * @param integer $tableId
     * @return UserTableStatistic
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
     * @param integer $userId
     * @return UserTableStatistic
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }



    /*methods for statistic
    */

    /*increase games*/
    public function increaseUserGamesCount()
    {
        $this->gamesPlayed = $this->gamesPlayed+1;
    }

    /**
     * @param integer $score
     */
    public function increaseUserScoredPoints($score)
    {
        $this->pointsScored = $this->pointsScored+$score;
    }

    public function increaseUserMissedPoints($score)
    {
        $this->pointsMissed = $this->pointsMissed + $score;
    }

    /**
     *
     */
    public function increaseUserWinGameCount()
    {
        $this->gamesWin = $this->gamesWin+1;
    }

    /**
     * @return int
     */
    public function getLostGames()
    {
        return $this->gamesPlayed-$this->gamesWin;
    }

    /**
     * @return float
     */
    public function getPlusMinusBalance()
    {
        if($this->gamesPlayed>0)
        return round(($this->pointsScored-$this->pointsMissed)/$this->gamesPlayed , 2 );
        return 0;
    }

    /*User all statistic*/



}
