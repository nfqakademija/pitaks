<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:18
 */

namespace pitaks\KickerBundle\EventListener;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use pitaks\KickerBundle\Event\ApiErrorEvent;

class ApiErrorListener {
    /**
     * @var LOGGER
     */
    protected $logger;


    public function setLogger($logger)
    { $this->logger = $logger; }
    public function getLogger()
    { return $this->logger; }

    public function onApiError (ApiErrorEvent $event)
    {
        $this->logger->error(strtr("{date} - {type} - {message}", [
            '{date}' =>  new \DateTime('now'),
            '{type}' => 'error',
            '{message}' => "error",
        ]));
    }
}