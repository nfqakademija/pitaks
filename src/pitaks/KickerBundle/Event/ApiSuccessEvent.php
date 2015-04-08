<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.08
 * Time: 20:14
 */

namespace pitaks\KickerBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class ApiSuccessEvent extends Event{
    const API_SUCCESS_EVENT = 'api_success';

}