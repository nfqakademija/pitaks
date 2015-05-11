<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.04
 * Time: 21:29
 */
namespace pitaks\KickerBundle\Services;

use pitaks\UserBundle\Services\UserStatisticService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use pitaks\KickerBundle\Entity\Game;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Entity\EventTable;
use pitaks\KickerBundle\Event\ApiErrorEvent;
use pitaks\KickerBundle\Event\ApiQueryChangeEvent;
use pitaks\KickerBundle\Event\ApiSuccessEvent;
use pitaks\KickerBundle\Module\ApiParams;
use pitaks\UserBundle\Entity\User;
use pitaks\UserBundle\Entity\UserTableStatistic;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\EventDispatcher;

class APIDataService extends ContainerAware
{

    const MIN_GAME_TIME = 60;

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
     * @param Tables $table
     * @return bool
     */
    public function getTableStatusFromApi($table)
    {
        $time = time();
        $lasAPI = $this->getJsonFromTableApi($table, 1)['records'][0]['timeSec'];
        if (($time - $lasAPI) < APIDataService::MIN_GAME_TIME) {
            $table->setIsFree(false);
            $this->getEm()->flush();
            return true;
        }
        else {
            $table->setIsFree(false);
            $this->getEm()->flush();
            return false;
        }
    }

    /**
     * @param Tables $table
     * @return mixed
     */
    public function getLastActiveTableApiJson($table)
    {
        return $this->getJsonFromTableApi($table, 1);
    }

    /**
     * @param Tables $table
     * @param $rows
     * @param null $fromID
     * @return mixed
     */
    public function getJsonFromTableApi($table, $rows, $fromID = null)
    {
        $client = new Client();
        $params = new ApiParams();

        $params->setUrl($table->getApiUrl())->setAuth([$table->getUserName(), $table->getPassword()]);

        $results = null;

        /**
         * @var EventDispatcherInterface $dispatcher
         */
        $dispatcher = $this->container->get('event_dispatcher');
        $successEvent = new ApiSuccessEvent();
        $errorEvent = new ApiErrorEvent();

        try {
            if (!$fromID) {
                $params->setParam('rows', $rows);
                $queryChangeEvent = new ApiQueryChangeEvent();
                $queryChangeEvent->setParams($params);
                //          echo sprintf("Rows at begining: %d\n", $params->getParam('row'));
                //             var_dump($params->getQueryString());
                //            $dispatcher->dispatch(ApiQueryChangeEvent::API_QUERY_EVENT, $queryChangeEvent);
                //           echo sprintf("Rows after dispatched event: %d\n", $params->getParam('row'));
                //           var_dump($params->getQueryString());
                $results = $client->get($params->getQueryString(), ['auth' => $params->getAuth()]);
                //           $dispatcher->dispatch('api_success', $successEvent);
                // die;
            } else {
                $results = $client
                    ->get($params->setParams([
                        'rows' => $rows,
                        'from-id' => $fromID,
                    ])
                        ->getQueryString(), ['auth' => $params->getAuth()]);
                $dispatcher->dispatch('api_success', $successEvent);
            }
        } catch (Exception $e) {

            echo 'Caught exceptions: ', $e->getMessage(), "\n";
            $dispatcher->dispatch('api_failed', $errorEvent);

        }
        return $results->json();
    }

    /**
     * @param $taleID
     */
    public function  getData($taleID)
    {
        /** need to check if id exits */
        if ($this->getEm()->getRepository('pitaksKickerBundle:Tables')->checkTableId($taleID)) {
            //we will execute code where:
            set_time_limit(0);
            $table = $this->getEm()->getRepository('pitaksKickerBundle:Tables')->findByID($taleID);
            $data = $this->getLastActiveTableApiJson($table);
            $lastGame = $this->getEm()->getRepository('pitaksKickerBundle:Game')->getLastGame($taleID);
                if ($lastGame == null) {
                    $lastGame=  $this->createNewGame($table,$data['records']);
                }
                    $lastGameID = $lastGame->getLastAddedEventId();
                $lastID = $data['records'][0]['id'];

                while ($lastGameID < $lastID) {
                    $data = $this->getJsonFromTableApi($table, 100, $lastGameID);
                    $records = $data['records'];

                    $this->handlerApiData($table, $records);

                    $lastGameID = $this->getEm()->getRepository('pitaksKickerBundle:Game')->getLastGame($taleID)->getLastAddedEventId();
                    echo('Last inserted id from API: ' . $lastGameID);
                }
        } else {
            echo("Invalid table id " . $taleID);
        }
    }

    /**
     * @param Tables $table
     * @param array $records
     */
    public function handlerApiData($table, $records)
    {
        set_time_limit(0);
        if (sizeof($this->getEm()->getRepository('pitaksKickerBundle:Game')->findAll()) > 0) {
            $game = $this->getLastGame($table->getId());
            if ($game->getEndEventId()) {
                $game = $this->createNewGame($table, $records);
            }
        } else {
            $game = $this->createNewGame($table, $records);
        }
        $scoreTeam1 = $game->getScoreTeam1();
        $scoreTeam2 = $game->getScoreTeam2();
        foreach ($records as $record) {

            //tikrinam laiko tarpa
            $recordTime = $record['timeSec'];
            echo "time1: " . $recordTime . " time2 " . $game->getLastTime() . "  " . ($recordTime - $game->getLastTime()) . "\n";
            if ($recordTime - $game->getLastTime() > $this::MIN_GAME_TIME) {
                echo "GALAS LAIKA";
                $game->setEndEventId($record['id']);
                $this->getEm()->flush();
                $this->container->get('user_statistic_service')->addUserStatistic($game);
                $scoreTeam1 = 0;
                $scoreTeam2 = 0;
                $game = $this->createNewGame($table, $records, $record);
            } else {
                $game->setLastAddedEventId($record['id']);
                $game->setLastTime($record['timeSec']);
                if ($record['type'] == "TableShake") {
                    echo "table shake  <br/>";
                } elseif ($record['type'] == "AutoGoal") {
                    $team = json_decode($record['data'], true);
                    if ($team['team'] == 0) {
                        $scoreTeam1 += 1;
                    } else {
                        $scoreTeam2 += 1;
                    }
                    echo "goal <br/>" . $scoreTeam1 . " : " . $scoreTeam2;
                    if ($scoreTeam1 == 10 || $scoreTeam2 == 10) {
                        echo "Game End";
                        $game->setScoreTeam1($scoreTeam1);
                        $game->setScoreTeam2($scoreTeam2);
                        $game->setEndEventId($record['id']);
                        $this->getEm()->flush();
                        $this->container->get('user_statistic_service')->addUserStatistic($game);
                        $scoreTeam1 = 0;
                        $scoreTeam2 = 0;
                        $game = $this->createNewGame($table, $records, $record);
                    }
                } elseif ($record['type'] == "CardSwipe") {
                    $player = json_decode($record['data'], true);
                    $this->checkCardSwipe($table,$player['card_id']);
                    if ($player['team'] == 0) {
                        if ($player['player'] == 0) {
                            $game->setUser1Team1($player['card_id']);
                        } else {
                            $game->setUser2Team1($player['card_id']);
                        }
                    } else {
                        if ($player['player'] == 0) {
                            $game->setUser1Team2($player['card_id']);
                        } else {
                            $game->setUser2Team2($player['card_id']);
                        }
                    }
                    $this->getEm()->flush();
                }
                $game->setLastTime($record['timeSec']);
                $game->setScoreTeam1($scoreTeam1);
                $game->setScoreTeam2($scoreTeam2);
                $this->getEm()->flush();
            }
        }
    }

    /**
     * @param $table
     * @param $cardID
     */
    public function checkCardSwipe($table,$cardID )
    {
        /** @var Game $game */
        $game= $this->getEm()->getRepository('pitaksKickerBundle:Game')->getLastGame($table->getId());
        if($cardID == $game->getUser1Team1()){
            $game->setUser1Team1(null);
            $this->em->flush();
        }
        elseif($cardID == $game->getUser1Team2() ){
            $game->setUser1Team2(null);
            $this->em->flush();
        }
        elseif($cardID == $game->getUser2Team1()){
            $game->setUser2Team1(null);
            $this->em->flush();
        }
        elseif($cardID == $game->getUser2Team2()){
            $game->setUser2Team2(null);
            $this->em->flush();
        }
    }
    /**
     * @param Tables $table
     * @param array $records
     * @return Game
     */
    public function createNewGame($table, $records, $lastRecord = null)
    {
        $game = new Game();
        $game->setTableId($table);//pasiduosim stalo id paskui
        $game->setScoreTeam1(0);
        $game->setScoreTeam2(0);

        /*count vietoj sizeof*/
        if (sizeof($this->getEm()->getRepository('pitaksKickerBundle:Game')->findAll()) < 1 || $lastRecord == null) {
            $game->setStartEventId($records[0]['id']);
            $game->setLastAddedEventId($records[0]['id']);
            $game->setLastTime($records[0]['timeSec']);
            $game->setBeginTime($records[0]['timeSec']);
        } else {
            $lastGame = $this->getEm()->getRepository('pitaksKickerBundle:Game')->getLastGame($table->getId());
            $game->setBeginTime($lastRecord['timeSec']);
            $game->setStartEventId($lastRecord['id']);//turetu uždėti pirmą?
            $game->setLastAddedEventId($lastRecord['id']);
            $game->setLastTime($lastRecord['timeSec']);

            //tikrinti laiko skirtuma nuo buvusio
            //tikrinsime ar zaidzia tie patys zaidejai
            if ($game->getLastTime() - $lastGame->getLastTime() > $this::MIN_GAME_TIME) {
                //reikia uzdeti buvusius vartotojus
                if (!is_null($lastGame->getUser1Team1()) || !is_null($lastGame->getUser2Team1()) ||
                    !is_null($lastGame->getUser1Team2()) || !is_null($lastGame->getUser2Team2())
                ) {
                    //uzmesti
                    $game->setUser1Team1($lastGame->getUser1Team1());
                    $game->setUser1Team2($lastGame->getUser2Team1());
                    $game->setUser2Team1($lastGame->getUser1Team2());
                    $game->setUser2Team2($lastGame->getUser2Team2());
                }

            }
        }

        $this->getEm()->persist($game);
        $this->getEm()->flush();
        return $game;
    }

    /**
     * @param $tableId
     * @return mixed
     */
    public function getLastGame($tableId)
    {
        $game = $this->getEm()->getRepository('pitaksKickerBundle:Game')->getLastGame($tableId);
        return $game;
    }

}
