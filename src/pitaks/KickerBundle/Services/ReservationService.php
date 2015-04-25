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

        $this->createRegisteredReservation($userId, $friendId, $startDate, $endDate);

        for($i = 0; $i<count($freeReservations); $i++){
            if($reservation->getId() == $freeReservations[$i]->getId())
            {
                $kiekis = 0;
                while($kiekis<$step){
                    $freeReservations[$i+$kiekis];
                    $freeReservations[$i+$kiekis]->setisFree(false);
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
     */
    function createRegisteredReservation($userId, $friendId, $startDate, $endDate){
        $registeredReservation = new RegisteredReservation();
        $registeredReservation->setDate(new \DateTime());
        $registeredReservation->setUserId($userId);
        $registeredReservation->setFriendId($friendId);
        $registeredReservation->setIsConfirmed(false);
        $registeredReservation->setReservationEnd(new \DateTime($endDate));
        $registeredReservation->setReservationStart(new \DateTime($startDate));
        $this->getEm()->persist($registeredReservation);
        $this->getEm()->flush();
    }

}