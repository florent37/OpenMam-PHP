<?php

namespace Tests\AppBundle\Controller;

use CoreBundle\Apk\Finder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    const USERNAME = 'foo';

    const PASSWORD = 'foo';

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

    public function testIAddAnApk()
    {
        $client = static::createClient();
        $client->request('GET', '/add');
        $crawler = $client->followRedirect();
        $client->submit(
            $crawler
                ->filter('form')
                ->form([
                    '_username' => self::USERNAME,
                    '_password' => self::PASSWORD
                ],
                'POST'
            )
        );
        $crawler = $client->followRedirect();


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/add', $crawler->getUri());
    }

    public function testITryToAddAnApkWithoutCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/my-apk-2name');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
