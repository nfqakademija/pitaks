<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:17
 */

namespace pitaks\KickerBundle\EventListener;
use Monolog\Logger;

use pitaks\KickerBundle\Event\ApiSuccessEvent;

class ApiSuccessListener {
    /**
     * @var LOGGER
     */
    protected $logger;


    public function setLogger($logger)
    { $this->logger = $logger; }
    public function getLogger()
    { return $this->logger; }

    public function onApiSuccess (ApiSuccessEvent $event)
    {
        echo "OK";
    }
}