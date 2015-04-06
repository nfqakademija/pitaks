<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.06
 * Time: 00:34
 */

namespace pitaks\KickerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ApiEvents extends Event {

    const API_SUCCESS_EVENT = 'api_success';
    const API_FAILED_EVENT = 'api_failed';

}