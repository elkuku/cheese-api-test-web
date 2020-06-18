<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest2 extends WebTestCase
{
    public function testTokenEmptyToken()
    {
        $client = static::createClient();

        // $client->request('GET', '/post/hello-world');

        $client->request(
            'POST',
            '/connect/google/api-token',
            [
                'idtoken' => '',
            ]
        );

        $content = $client->getResponse()->getContent();

        $o = json_decode($content, false);

        var_dump($o);


        // var_dump($r->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testTokenInvalidToken1()
    {
        $client = static::createClient();

        // $client->request('GET', '/post/hello-world');

        $crawler = $client->request(
            'POST',
            '/connect/google/api-token',
            [
                'idtoken' => '',
            ]
        );

        $r = $client->getResponse();

        var_dump($r->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
