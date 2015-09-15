<?php

namespace AccessBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
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
