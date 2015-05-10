<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.22
 * Time: 19:48
 */

namespace pitaks\KickerBundle\Services;

use Doctrine\ORM\EntityManager;
use pitaks\KickerBundle\Entity\Reservation;
use pitaks\KickerBundle\Entity\RegisteredReservation;
use pitaks\TeamBundle\Entity\Team;
use pitaks\TeamBundle\Entity\TeamReservation;
use pitaks\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationService extends ContainerAware{
    /**
     * reservation duration in seconds
     */
    const RESERVATION_DURATION=900;
    /**
     * Reservation day start by hours
     */
    const RESERVATION_DAY_START=7;
    /**
     * Reservation (work hours) TODO need to think about how to make it dynamic
     */
    const RESERVATION_DAY_LONG=15;

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * Creates reservation blocks for all tables
     */
    public function createRegistrationBlocksForTables()
    {
        $tables = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->findAll();
        foreach($tables as $table )
        {
            $this->createFreeReservationDayTimes($table->getId());
        }
    }
    /**
     * created blocks of registration for 1 table (1week)
     * @param integer $tableId
     */
    public function createFreeReservationDayTimes($tableId){
        //find last table reservation
        $lastReservationBox= $this->getEm()->getRepository('pitaksKickerBundle:Reservation')
            ->findBy(array('tableId'=>$tableId),array('reservationStart' => 'DESC'),1);
        if(count($lastReservationBox)>0)
        {
            $lastTime=$lastReservationBox[0]->getReservationStart();
        }
        else{
            $lastTime=new \DateTime();
        }
        //susikuriamas time
        $newtime = strtotime($lastTime->format('Y-m-d'));

        for($i = 1; $i <= 7; $i++)
        {
            $newtime= strtotime("+1 day", $newtime);

            $timeToday = $newtime + $this::RESERVATION_DAY_START*3600;

            $endDayTime = strtotime("+".$this::RESERVATION_DAY_LONG." hour", $timeToday);

            $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->find($tableId);

            $time = $timeToday;

            while($time<$endDayTime){
                //start time
                $startTime = date('Y-m-d H:i', $time);
                //end time
                $time = $time + $this::RESERVATION_DURATION;
                $endTime = date('Y-m-d H:i', $time);
                $this->saveReservationBlocks($startTime,$endTime,$table);
            }
            echo date('Y-m-d',$newtime);
        }
    }

    /**
     * @param $day
     * @param int $duration
     * @param string $startTime
     * @param int $tableId
     * @return array
     */
    public function showFreeTableReservations($tableId,$day, $duration= 900,$startTime="06:00"){
        //list of free reservations
        $reservations = $this->em->getRepository('pitaksKickerBundle:Reservation')->findFreeDateReservations($tableId,$day);

        $dayfreeTime= array();

        $step = ($duration/$this::RESERVATION_DURATION);
        for($i = 0; $i<count($reservations)-($step-1); $i++)
        {
            $reservation = $reservations[$i];
            if($reservation->getReservationStartHour() > $startTime) {
                if(strtotime($reservation->getReservationStartHour())+$duration
                        == strtotime($reservations[$i+$step-1]->getReservationEndHour())){
                    $freeTimes = array(
                        "begin" => $reservation->getReservationStartHour(),
                        "end" => $reservations[$i+$step-1]->getReservationEndHour());
                    $dayfreeTime[] = $freeTimes;
                }
            }
        }
        return $dayfreeTime;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param integer $tableId
     */
    public function saveReservationBlocks($startDate, $endDate,$tableId){
        $reservation = new Reservation();

        $start =new \DateTime($startDate);
        $end =new \DateTime($endDate);

        $reservation->setReservationStart($start);
        $reservation->setReservationEnd($end);
        $reservation->setReservationDuration($this::RESERVATION_DURATION);
        $reservation->setIsFree(true);
        $reservation->setTableId($tableId);
        $this->getEm()->persist($reservation);
        $this->getEm()->flush();
    }


    /**
     *
     * @param $date
     * @param $tableId
     * @param $startValue
     * @param $endValue
     * @param null $userId
     * @param null $friendId
     */
    public function saveUserReservation($date, $tableId, $startValue, $endValue,$userId=null, $friendId=null)
    {
        $startDate = $date." ".$startValue;
        $endDate =$date." ".$endValue;
        $duration = strtotime($endValue) - strtotime($startValue);
        $reservationTime = new \DateTime($startDate);

        $step = $duration/$this::RESERVATION_DURATION;

        $reservation= $this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findOneBy(array('reservationStart' => $reservationTime, 'tableId' => $tableId));

        $freeReservations=$this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findFreeDateReservations($tableId,$date);

        $registeredReservation =$this->createRegisteredReservation($userId, $friendId, $startDate, $endDate);

        for($i = 0; $i<count($freeReservations); $i++){
            if($reservation->getId() == $freeReservations[$i]->getId())
            {
                $kiekis = 0;
                while($kiekis<$step){
                    $freeReservations[$i+$kiekis];
                    $freeReservations[$i+$kiekis]->setIsFree(false);
                    $freeReservations[$i+$kiekis]->setRegisteredReservationId($registeredReservation);
                    $this->getEm()->flush();
                    $kiekis++;
                }
                break;
            }
        }
    }

    public function saveTeamReservation($date, $tableId, $startValue, $endValue,$teamId,$anotherTeamId)
    {
        $startDate = $date." ".$startValue;
        $endDate =$date." ".$endValue;
        $duration = strtotime($endValue) - strtotime($startValue);
        $reservationTime = new \DateTime($startDate);

        $step = $duration/$this::RESERVATION_DURATION;

        $reservation= $this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findOneBy(array('reservationStart' => $reservationTime, 'tableId' => $tableId));

        $freeReservations=$this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findFreeDateReservations($tableId,$date);

        $registeredReservation =$this->createTeamReservation($teamId,$anotherTeamId, $startDate, $endDate);

        for($i = 0; $i<count($freeReservations); $i++){
            if($reservation->getId() == $freeReservations[$i]->getId())
            {
                $kiekis = 0;
                while($kiekis<$step){
                    $freeReservations[$i+$kiekis];
                    $freeReservations[$i+$kiekis]->setIsFree(false);
                    $freeReservations[$i+$kiekis]->setTeamReservation($registeredReservation);
                    $this->getEm()->flush();
                    $kiekis++;
                }
                break;
            }
        }
    }

    /**
     * @param $userId
     * @param $friendId
     * @param $startDate
     * @param $endDate
     * @return int
     */
    function createRegisteredReservation($userId, $friendId, $startDate, $endDate){
        $registeredReservation = new RegisteredReservation();
        $registeredReservation->setDate(new \DateTime());
        $registeredReservation->setUserId($userId);
        $registeredReservation->setFriendId($friendId);
        if($friendId == null) {
            $registeredReservation->setIsConfirmed(true);
        }
        else{
            $registeredReservation->setIsConfirmed(false);
        }
        $registeredReservation->setReservationEnd(new \DateTime($endDate));
        $registeredReservation->setReservationStart(new \DateTime($startDate));
        $this->getEm()->persist($registeredReservation);
        $this->getEm()->flush();
        return $registeredReservation;
    }

    /**
     * @param $teamId
     * @param $anotherTeamId
     * @param $startDate
     * @param $endDate
     * @return TeamReservation
     */
    public function createTeamReservation($teamId,$anotherTeamId, $startDate, $endDate)
    {
        $myTeam = $this->getEm()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $competitor = $this->getEm()->getRepository('pitaksTeamBundle:Team')->find($anotherTeamId);
        $registeredReservation = new TeamReservation();
        $registeredReservation->setDate(new \DateTime());
        $registeredReservation->setReservationEnd(new \DateTime($endDate));
        $registeredReservation->setReservationStart(new \DateTime($startDate));
        $registeredReservation->setCompetitorTeam( $competitor);
        $registeredReservation->setTeam($myTeam);
        $registeredReservation->setIsConfirmed(false);
        $this->getEm()->persist($registeredReservation);
        $this->getEm()->flush();
        return $registeredReservation;
    }


    /**
     * @param integer $registeredReservationId
     */
    public function deleteRegisteredReservation($registeredReservationId)
    {
        $registeredReservation=$this->getEm()->
        getRepository('pitaksKickerBundle:RegisteredReservation')->find($registeredReservationId);
        if($registeredReservation){
        $reservations = $registeredReservation->getReservations();
        foreach($reservations as $reservation)
        {
            $reservation->setIsFree(true);
            $reservation->setRegisteredReservationId(null);
        }
        $this->getEm()->flush();
        $this->getEm()->remove($registeredReservation);
        $this->getEm()->flush();}
    }

    /**
     * @param $registeredReservationId
     */
    public function acceptUnconfirmedRegisteredReservation($registeredReservationId){
        $registeredReservation=$this->getEm()->
        getRepository('pitaksKickerBundle:RegisteredReservation')->find($registeredReservationId);
        if($registeredReservation) {
            $registeredReservation->setIsConfirmed(true);
            $this->getEm()->flush();
        }
    }


    /**
     * @param integer $registeredReservationId
     */
    public function deleteTeamRegisteredReservation($registeredReservationId)
    {
        $registeredReservation=$this->getEm()->
        getRepository('pitaksTeamBundle:TeamReservation')->find($registeredReservationId);
        if($registeredReservation){
        $reservations = $registeredReservation->getReservations();
        foreach($reservations as $reservation)
        {
            $registeredReservation->removeReservation($reservation);
            /** @var Reservation $reservation */
            $reservation->setIsFree(true);
            $reservation->setTeamReservation(null);
        }
        $registeredReservation->getTeam()->removeReservation($registeredReservation);
        $registeredReservation->getCompetitorTeam()->removeInvitedReservation($registeredReservation);
            $registeredReservation->setTeam(null);
            $registeredReservation->setCompetitorTeam(null);
        $this->getEm()->remove($registeredReservation);
        $this->getEm()->flush();
        }
    }
    /**
     * @param $registeredReservationId
     */
    public function acceptUnconfirmedTeamRegisteredReservation($registeredReservationId){
        $registeredReservation=$this->getEm()->
        getRepository('pitaksTeamBundle:TeamReservation')->find($registeredReservationId);
        if($registeredReservation){
        $registeredReservation->setIsConfirmed(true);
        $this->getEm()->flush();}
    }

    /*Need to erase reservation blocks and Registered Reservations*/

    public function regularDeleting()
    {
        /*get data Today and delete all reservations
        *where end time expire
         * */
        $today=new \DateTime();
        $date = $today->format('Y-m-d H:i');
        $RegisteredReservations = $this->getEm()->getRepository('pitaksKickerBundle:RegisteredReservation')
            ->findOlderThenData($date);
        $ReservationBlocks = $this->getEm()->getRepository('pitaksKickerBundle:Reservation')
            ->findOlderThenData($date);
        echo "Reservations which are older then ".$date.". Deleting";

        foreach($ReservationBlocks as $row){
            $this->getEm()->remove($row);
            $this->getEm()->flush();
        }
        foreach($RegisteredReservations as $row){
            $this->getEm()->remove($row);
            $this->getEm()->flush();
        }

    }

    /**
     * @param User $user
     * @return array
     */
    public function upcomingUserReservations($user)
    {
        //need to find all reservations
        return $this->getEm()->getRepository('pitaksKickerBundle:RegisteredReservation')
            ->findAllUserUpcomingReservations($user->getUsername(),new \DateTime());
    }
    /**
     * @param User $user
     * @return array
     */
    public function upcomingUserTeamsReservations($user)
    {
        $reservations=array();
        $teams = $user->getTeams();
        foreach($teams as $team)
        {
            /**
             * @var Team $team
             */
            $teamsReservations = $team->getReservations();
            foreach($teamsReservations as $reservation)
            {
                /** @var  TeamReservation $reservation*/
                if($reservation->getIsConfirmed() == true && $reservation->getReservationStart() > new \DateTime())
                {
                    $reservations[]=$reservation;
                }
            }
            $teamsInvitedReservations=$team->getInvitedReservations();
            foreach($teamsInvitedReservations as $reservation)
            {
                /** @var  TeamReservation $reservation*/
                if($reservation->getIsConfirmed() == true && $reservation->getReservationStart() > new \DateTime())
                {
                    $reservations[]=$reservation;
                }
            }
        }
       return $reservations;
    }
}