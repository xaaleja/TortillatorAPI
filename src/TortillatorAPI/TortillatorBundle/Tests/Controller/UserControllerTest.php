<?php

namespace TortillatorAPI\TortillatorBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use TortillatorAPI\TortillatorBundle\Tests\Fixtures\Entity\LoadUserData;


class UserControllerTest extends WebTestCase
{
    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testGet()
    {
        $fixtures = array('TortillatorAPI\TortillatorBundle\Tests\Fixtures\Entity\LoadUserData');
        $this->customSetUp($fixtures);
        $user = array_pop(LoadUserData::$users);

        $route =  $this->getUrl('api_1_get_user', array('username' => $user->getUsername(), '_format' => 'json'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['username']));
    }

    public function testJsonPostUserAction()
    {
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            '/api/v1/users.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"user1" "password":"pass1","email":"email1@gmail.com", "city":"city1"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }
    public function testJsonPostUserActionShouldReturn400WithBadParameters()
    {
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            '/api/v1/users.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"ninja":"turtles"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

    public function testJsonPutUserActionShouldModify()
    {
        $fixtures = array('TortillatorAPI\TortillatorBundle\Tests\Fixtures\Entity\LoadUserData');
        $this->customSetUp($fixtures);
        $users = LoadUserData::$users;
        $user = array_pop($users);

        $this->client->request('GET', sprintf('/api/v1/users/%d.json', $user->getUsername()), array('ACCEPT' => 'application/json'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $this->client->request(
            'PUT',
            sprintf('/api/v1/users/%d.json', $user->getUsername()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"password":"pass2","email":"user2@gmail.com", "city":"City2"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/users/%d.json', $user->getUsername())
            ),
            $this->client->getResponse()->headers
        );
    }

    public function testJsonPutUserActionShouldCreate()
    {
        $username = "";
        $this->client->request('GET', sprintf('/api/v1/users/%d.json', $username), array('ACCEPT' => 'application/json'));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $this->client->request(
            'PUT',
            sprintf('/api/v1/users/%d.json', $username),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"password":"pass2","email":"user2@gmail.com", "city":"City2"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }
}
