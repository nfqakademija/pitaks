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

    public function createFreeReservationDayTimes(){

        $today = new \DateTime();
        //gauname tik diena
        $timeToday = strtotime($today->format('Y-m-d'));
        //pradedame nuo 7
        $timeToday = $timeToday + $this::RESERVATION_DAY_START*3600;
        //spausdina data nuo 7

        $endDayTime = strtotime("+".$this::RESERVATION_DAY_LONG." hour", $timeToday);

        $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->find(1);

        $time = $timeToday;

        while($time<$endDayTime){
            //start time
            $startTime = date('Y-m-d H:i', $time);
            //end time
            $time = $time + $this::RESERVATION_DURATION;
            $endTime = date('Y-m-d H:i', $time);
            $this->saveReservationBlocks($startTime,$endTime,$table);
        }

       // $date = strtotime("+1 month", strtotime("2007-01-29"));

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
        echo "Your date: ".$startDate." duration ". $duration;
        $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->find($tableId);

        $reservationTime = new \DateTime($startDate);


        $step = $duration/$this::RESERVATION_DURATION;


        $reservation= $this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findOneBy(array('reservationStart' => $reservationTime, 'tableId' => $tableId));

        $freeReservations=$this->getEm()->
        getRepository('pitaksKickerBundle:Reservation')->findFreeDateReservations($tableId,$date);

        echo "".$reservation->getId();
        $this->createRegisteredReservation($userId, $friendId, $startDate, $endDate);

        for($i = 0; $i<count($freeReservations); $i++){
            if($reservation->getId() == $freeReservations[$i]->getId())
            {
                echo "setiname...";
                $kiekis = 0;
                while($kiekis<$step){
                    $freeReservations[$i+$kiekis];
                    echo($freeReservations[$i+$kiekis]->getReservationStartHour());
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