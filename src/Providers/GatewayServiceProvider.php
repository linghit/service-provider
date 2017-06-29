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

        $middleware = new AuthMiddleware();

        /**
         * 遍历路由, 往每个路由中手工添加中间件
         */
        foreach (route()->aliasMap as $group) {
            foreach ($group as $route) {
                $route->withAddMiddleware($middleware);
            }
        }

        /**
         * 注册全局中间件
         */
//        $container->get('dispatcher')->withAddMiddleware(new AuthMiddleware());

        /**
         * 健康检查
         */
        route()->get('/health_check', function () {
            return json([
                'status' => 200,
            ]);
        });
    }
}
