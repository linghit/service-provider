<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

namespace Exts\ServiceGateway\Providers;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Routing\RouteDispatcher;
use Exts\ServiceGateway\Router;

class RouteServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $router = new Router();
        $dispatcher = new RouteDispatcher($router, $container['config']->get('middleware', []));

        $container->add('router', $router);
        $container->add('dispatcher', $dispatcher);

        include app()->getPath().'/config/routes.php';
    }
}
