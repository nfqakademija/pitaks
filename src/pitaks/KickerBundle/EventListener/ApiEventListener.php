<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.06
 * Time: 01:16
 */

namespace pitaks\KickerBundle\EventListener;

use pitaks\KickerBundle\Event\ApiEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiEventListener {

    public function onApiSuccess (ApiEvents $event)
    {
        echo "Ok";
    }
    public function onApiError (ApiEvents $event)
    {
        echo "Fail";
    }

}