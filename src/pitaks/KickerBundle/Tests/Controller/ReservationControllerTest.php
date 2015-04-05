<?php

namespace pitaks\KickerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    public function testNewreservation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/newReservation');
    }

    public function testDeletereservation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteReservation');
    }

    public function testEditreservation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/editReservation');
    }

}
