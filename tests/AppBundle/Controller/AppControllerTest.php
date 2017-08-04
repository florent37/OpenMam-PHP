<?php

namespace Tests\AppBundle\Controller;

use CoreBundle\Apk\Finder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function setUp()
    {
        static::$class = 'AppKernel';
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAdd()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/add');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/my-apk-2name');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
