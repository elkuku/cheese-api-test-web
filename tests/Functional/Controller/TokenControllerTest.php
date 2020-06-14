<?php

namespace App\Tests\Functional\Controller;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TokenControllerTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testPOSTCreateToken()
    {
        $client = self::createClient();

        $this->createUserAndLogIn($client, 'weaverryan@example.com', 'I<3Pizza');


        $response = $client->request('POST', '/api/tokens', [
            'auth_basic' => ['weaverryan', 'I<3Pizza']
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // $this->assertResponsePropertyExists(
        //     $response,
        //     'token'
        // );
        self::assertArrayHasKey('token', $client->getResponse()->toArray());
    }

    public function testPOSTTokenInvalidCredentials()
    {
        $client = self::createClient();
        $this->createUserAndLogIn($client, 'weaverryan', 'I<3Pizza');
        $response = $client->request('POST', '/api/tokens', [
            'auth_basic' => ['weaverryan', 'IH8Pizza']
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
