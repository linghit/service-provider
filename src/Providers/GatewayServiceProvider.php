<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-04
 */

namespace Exts\ServiceGateway\Providers;

use Exts\ServiceGateway\Consumer;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Exts\ServiceGateway\Middlewares\AuthMiddleware;

class GatewayServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        /**
         * 注册服务网关消费者
         */
        $container->add('gateway_consumer', new Consumer());

        /**
         * 注册全局中间件
         */
        $container->get('dispatcher')->withAddMiddleware(new AuthMiddleware());

        route()->get('/health_check', function () {
            return 'ok.';
        });
    }
}
