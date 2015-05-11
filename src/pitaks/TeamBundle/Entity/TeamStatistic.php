<?php

namespace pitaks\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;

/**
 * TeamStatistic
 *
 * @ORM\Table( uniqueConstraints={@UniqueConstraint(name="rating_unique", columns={"table", "team"})})
 * @ORM\Entity(repositoryClass="pitaks\TeamBundle\Entity\TeamStatisticRepository")
 */
class TeamStatistic
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
     * @ORM\Column(name="`gamesPlayed`", type="integer",nullable=true)
     */
    private $gamesPlayed;

    /**
     * @var integer
     *
     * @ORM\Column(name="`gamesWin`", type="integer",nullable=true)
     */
    private $gamesWin;

    /**
     * @var integer
     *
     * @ORM\Column(name="`pointsScored`", type="integer",nullable=true)
     */
    private $pointsScored;

    /**
     * @var integer
     *
     * @ORM\Column(name="`pointsMissed`", type="integer",nullable=true)
     */
    private $pointsMissed;

    /**
     * @ORM\ManyToOne(targetEntity="\pitaks\KickerBundle\Entity\Tables", inversedBy="tableTeamStatistic")
     * @ORM\JoinColumn(name="`table`", referencedColumnName="id")
     */
    private $table;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="stats")
     * @ORM\JoinColumn(name="`team`", referencedColumnName="id")
     */
    private $team;

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
     * @return TeamStatistic
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
     * @return TeamStatistic
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
     * @return TeamStatistic
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
     * @return TeamStatistic
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
     * Set table
     *
     * @param \pitaks\KickerBundle\Entity\Tables $table
     * @return TeamStatistic
     */
    public function setTable(\pitaks\KickerBundle\Entity\Tables $table = null)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return \pitaks\KickerBundle\Entity\Tables 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set team
     *
     * @param \pitaks\TeamBundle\Entity\Team $team
     * @return TeamStatistic
     */
    public function setTeam(\pitaks\TeamBundle\Entity\Team $team = null)
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



    /*methods for statistic
    */

    /*increase games*/
    public function increaseGamesCount()
    {
        $this->gamesPlayed = $this->gamesPlayed+1;
    }

    /**
     * @param integer $score
     */
    public function increaseScoredPoints($score)
    {
        $this->pointsScored = $this->pointsScored+$score;
    }

    public function increaseMissedPoints($score)
    {
        $this->pointsMissed = $this->pointsMissed + $score;
    }

    /**
     *
     */
    public function increaseWinGameCount()
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

}
