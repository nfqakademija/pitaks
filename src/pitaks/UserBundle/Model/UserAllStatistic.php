<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 17:45
 */
namespace pitaks\UserBundle\Model;

class UserAllStatistic {
    /**
     * @var integer
     */
    protected $gamesPlayed;
    /**
     * @var integer
     */
    protected $gamesWon;
    /**
     * @var integer
     */
    protected $pointsScored;
    /**
     * @var integer
     */
    protected $pointsMissed;
    /**
     * @var string
     */
    protected $username;

    function __construct()
    {
        $this->gamesPlayed = 0;
        $this->gamesWon = 0;
        $this->pointsScored = 0;
        $this->pointsMissed = 0;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function getGamesPlayed()
    {
        return $this->gamesPlayed;
    }

    /**
     * @param int $gamesPlayed
     */
    public function setGamesPlayed($gamesPlayed)
    {
        $this->gamesPlayed = $gamesPlayed;
    }

    /**
     * @return int
     */
    public function getGamesWon()
    {
        return $this->gamesWon;
    }

    /**
     * @param int $gameWon
     */
    public function setGamesWon($gameWon)
    {
        $this->gamesWon = $gameWon;
    }

    /**
     * @return int
     */
    public function getPointsScored()
    {
        return $this->pointsScored;
    }

    /**
     * @param int $pointsScored
     */
    public function setPointsScored($pointsScored)
    {
        $this->pointsScored = $pointsScored;
    }

    /**
     * @return int
     */
    public function getPointsMissed()
    {
        return $this->pointsMissed;
    }

    /**
     * @param int $pointsMissed
     */
    public function setPointsMissed($pointsMissed)
    {
        $this->pointsMissed = $pointsMissed;
    }

    /**
     * @return int
     */
    public function getLostGames()
    {
        return $this->gamesPlayed-$this->gamesWon;
    }

    /**
     * @return float
     */
    public function getPlusMinusBalance()
    {
        if($this->gamesPlayed>0)
        return  round( ($this->pointsScored-$this->pointsMissed)/$this->gamesPlayed,2);
        return 0;
    }

    public function increase($gamesPlayed,$gamesWon,$pointsScored,$pointsMissed)
    {
        $this->gamesWon+=$gamesWon;
        $this->gamesPlayed+=$gamesPlayed;
        $this->pointsMissed+=$pointsMissed;
        $this->pointsScored+=$pointsScored;
    }
}