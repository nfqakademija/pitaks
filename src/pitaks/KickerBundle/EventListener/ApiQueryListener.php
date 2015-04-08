<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:19
 */

namespace pitaks\KickerBundle\EventListener;


use pitaks\KickerBundle\Event\ApiQueryChangeEvent;

class ApiQueryListener {
    public function onChange(ApiQueryChangeEvent $event)
    {
        $params = $event->getParams();
        $params->setParam('row', 80);
    }
}