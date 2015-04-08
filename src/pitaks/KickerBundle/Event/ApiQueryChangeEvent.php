<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:15
 */

namespace pitaks\KickerBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class ApiQueryChangeEvent extends Event {
    const API_QUERY_EVENT = 'api_query';

}