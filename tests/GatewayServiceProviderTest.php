<?php

/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

use FastD\TestCase;
use FastD\Application;
use FastD\Http\Response;

class GatewayServiceProviderTest extends TestCase
{

    public function testMiddleware()
    {
        $response = $this->handleRequest($this->request('GET', '/'));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        $this->assertSame(false, gateway_consumer()->exists());


        $response = $this->handleRequest($this->request('GET', '/', [
            'HTTP_X_CONSUMER_CUSTOM_ID' => 1,
        ]));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertSame(true, gateway_consumer()->exists());
        $this->assertEquals(
            [
                'name' => 'hello',
            ],
            gateway_consumer()->getArrayCopy()
        );
    }

    public function createApplication()
    {
        return new Application(__DIR__ . '/app/minimal');
    }


}
