<?php

namespace pitaks\KickerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TableControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testCreatetable()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createTable');
    }

    public function testGettableevents()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getTableEvents');
    }

}
