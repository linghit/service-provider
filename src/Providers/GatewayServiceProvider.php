<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-04
 */

namespace Exts\ServiceGateway\Providers;

use Exts\ServiceGateway\Consumer;
use Exts\ServiceGateway\Contracts\ConsumerBuilderInterface;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Exts\ServiceGateway\Middlewares\AuthMiddleware;
use ReflectionClass;

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
         * 注册消费者建造者
         */
        if ($container->get('config')->has('gateway.consumer_builder')) {
            $reflection = new ReflectionClass($container->get('config')->get('gateway.consumer_builder'));
            if ($reflection->isSubclassOf(ConsumerBuilderInterface::class)) {
                $container->add('consumer_builder', $reflection->newInstance());
            }
        }

        /**
         * 遍历路由, 往每个路由中手工添加中间件
         */

        $middleware = new AuthMiddleware();
        app()->get('dispatcher')->before($middleware);

        /**
         * 健康检查
         */
        route()->head('/health-check', function () {
            return json([
                'status' => 200,
            ]);
        });
    }
}
