<?php

namespace App\Tests\Functional\Controller;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TokenControllerTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testPOSTCreateTokenMissingToken(): void
    {
        $client = self::createClient();

        // $this->createUserAndLogIn($client, 'weaverryan@example.com', 'I<3Pizza');

        $response = $client->request('POST', '/connect/google/api-token', [
            'auth_basic' => ['weaverryan', 'I<3Pizza']
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseArray = $response->toArray(false);
        // var_dump($client->getResponse()->toArray());
        // var_dump($responseArray);
        // $this->assertResponsePropertyExists(
        //     $response,
        //     'token'
        // );
        self::assertArrayHasKey('error', $responseArray);
        self::assertEquals('Missing token', $responseArray['error']);
        // self::assertArrayHasKey('token', $response->toArray(false));
    }

    // public function testPOSTCreateTokenBadToken1(): void
    // {
    //     $client = self::createClient();
    //
    //     // $this->createUserAndLogIn($client, 'weaverryan@example.com', 'I<3Pizza');
    //
    //     $response = $client->request(
    //         'POST',
    //         '/connect/google/api-token',
    //         [
    //             'query' => [
    //             'idtoken' => 'a'
    //             ]
    //     ]
    //     );
    //
    //     $this->assertEquals(200, $response->getStatusCode());
    //
    //     $responseArray = $response->toArray(false);
    //     // var_dump($client->getResponse()->toArray());
    //     // var_dump($responseArray);
    //     // $this->assertResponsePropertyExists(
    //     //     $response,
    //     //     'token'
    //     // );
    //     self::assertArrayHasKey('error', $responseArray);
    //     self::assertEquals('Missing token', $responseArray['error']);
    //     // self::assertArrayHasKey('token', $response->toArray(false));
    // }

    // public function testPOSTTokenInvalidCredentials()
    // {
    //     $client = self::createClient();
    //     $this->createUserAndLogIn($client, 'weaverryan', 'I<3Pizza');
    //     $response = $client->request('POST', '/connect/google/api-token', [
    //         'auth_basic' => ['weaverryan', 'IH8Pizza']
    //     ]);
    //     $this->assertEquals(401, $response->getStatusCode());
    // }
}
