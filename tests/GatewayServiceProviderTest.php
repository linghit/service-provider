<?php

/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

namespace Exts\ServiceGateway\Testing;

use FastD\TestCase;
use FastD\Application;
use FastD\Http\Response;

class GatewayServiceProviderTest extends TestCase
{

    public function createApplication()
    {
        return new Application(__DIR__ . '/app/minimal');
    }

    public function testMiddleware()
    {
        $response = $this->handleRequest($this->request('GET', '/'));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());


        $response = $this->handleRequest($this->request('GET', '/', [
            'HTTP_X_CONSUMER_CUSTOM_ID' => 1,
        ]));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEquals(
            [
                'name' => 'hello',
                'id' => 1,
            ],
            gateway_consumer()->getArrayCopy()
        );

        $response = $this->handleRequest($this->request('GET', '/', [
            'HTTP_X_CONSUMER_CUSTOM_ID' => 2,
        ]));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(
            [
                'name' => 'hello',
                'id' => 2,
            ],
            gateway_consumer()->getArrayCopy()
        );

        $response = $this->handleRequest($this->request('GET', '/', [
            'HTTP_X_CONSUMER_CUSTOM_ID' => 3,
        ]));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(
            [],
            gateway_consumer()->getArrayCopy()
        );
    }

    public function testHealthCheck()
    {
        $response = $this->handleRequest($this->request('HEAD', '/health-check'));

        $this->equalsJson(
            $response,
            [
                'status' => 200,
            ]
        );
    }
}
