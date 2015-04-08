<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:18
 */

namespace pitaks\KickerBundle\EventListener;


use pitaks\KickerBundle\Event\ApiErrorEvent;

class ApiErrorListener {
    public function onApiError (ApiErrorEvent $event)
    {
        echo "Fail";
    }

}