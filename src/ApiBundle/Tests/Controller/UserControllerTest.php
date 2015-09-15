<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetme()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getMe');
    }

    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/get');
    }

}
