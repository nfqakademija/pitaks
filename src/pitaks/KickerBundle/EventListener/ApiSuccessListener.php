<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:17
 */

namespace pitaks\KickerBundle\EventListener;


use pitaks\KickerBundle\Event\ApiSuccessEvent;

class ApiSuccessListener {
    public function onApiSuccess (ApiSuccessEvent $event)
    {
        echo "Ok";
    }
}