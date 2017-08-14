<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    const USERNAME = 'foo';

    const PASSWORD = 'foo';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        static::$class = 'ApiKernel';
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/'); // We are in "/api" by default with ApiKernel.
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $crawler = $this->client->request('GET', '/my-apk-2name');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testITryToUploadWithoutCredential()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request(
            'POST',
            '/upload'
        );

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testUpload()
    {
        $this->client = static::createClient();
        list($name, $version, $code) = ['my-apk-name', '1.0.0', 10];

        $filePath = sprintf(
            '%s/%s/%s/%s.apk',
            $name,
            $version,
            $code,
            $name
        );

        $fullFilePath = $this->container->getParameter('apk_folder').'/'.$filePath;
        $this->assertFalse(file_exists($fullFilePath));
        $crawler = $this->client->request(
            'POST',
            '/upload',
            [],
            [],
            [
                'HTTP_apk-name' => $name,
                'HTTP_apk-version' => $version,
                'HTTP_apk-code' => $code,
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->getToken()
            ],
            file_get_contents(__DIR__.'/../fixtures/data/apk/my-apk-2name/1.0.2/12/my-apk-name.apk')
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertFileExists($fullFilePath);
        $this->removeTmpFolder($fullFilePath, $filePath);
    }

    /**
     * Retrieve an available token.
     *
     * @return string
     */
    private function getToken() : string
    {
        $this->client->request(
            'POST',
            '/login_check',
            [
                '_username' => self::USERNAME,
                '_password' => self::PASSWORD,
            ]
        );

        $token = json_decode($this->client->getResponse()->getContent());

        return $token->token;
    }

    private function removeTmpFolder($fullFilePath, $filePath)
    {
        $apkFolder = $this->container->getParameter('apk_folder');
        @unlink($fullFilePath);
        $path = dirname($fullFilePath);
        while ($apkFolder !== $path) {
            rmdir($path);
            $path = dirname($path);
        }
    }
}
