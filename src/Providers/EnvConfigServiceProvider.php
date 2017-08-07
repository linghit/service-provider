<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-08
 */

namespace Exts\ServiceGateway\Providers;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class EnvConfigServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        if (file_exists(app()->getPath() . '/.env.yml')) {
            config()->load(app()->getPath().'/.env.yml');
        }
    }
}
