<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:14
 */

namespace pitaks\KickerBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class ApiErrorEvent extends Event{
    const API_FAILED_EVENT = 'api_failed';

}