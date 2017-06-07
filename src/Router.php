<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

namespace Exts\ServiceGateway;

use FastD\Routing\Exceptions\RouteException;
use FastD\Routing\RouteCollection;

class Router extends RouteCollection
{

    protected function concat($callback)
    {
        if (is_string($callback)) {
            return '\\Controller\\'.$callback;
        }

        return $callback;
    }

    public function get($path, $callback, array $defaults = [])
    {
        return parent::get($path, $this->concat($callback), $defaults);
    }

    public function post($path, $callback, array $defaults = [])
    {
        return parent::post($path, $this->concat($callback), $defaults);
    }

    public function patch($path, $callback, array $defaults = [])
    {
        return parent::patch($path, $this->concat($callback), $defaults);
    }

    public function delete($path, $callback, array $defaults = [])
    {
        return parent::delete($path, $this->concat($callback), $defaults);
    }

    public function head($path, $callback, array $defaults = [])
    {
        return parent::head($path, $this->concat($callback), $defaults);
    }

    public function resource($path, $controller, array $config = [])
    {
        if (!is_string($controller)) {
            throw new RouteException('closure is not supported yet');
        }

        $this->registerRestfulRoutes(
            rtrim($path, '/'),
            $controller,
            (isset($config['middleware']) ? $config['middleware'] : [])
        );
    }

    protected function registerRestfulRoutes($path, $controller, $middleware = [])
    {
        $this
            ->get([$path, 'name' => "{$path}.index"], "{$controller}@index")
            ->withMiddleware($middleware);
        $this
            ->post([$path, 'name' => "{$path}.store"], "{$controller}@store")
            ->withMiddleware($middleware);
        $this
            ->get(["{$path}/{id}", 'name' => "{$path}.show"], "{$controller}@show")
            ->withMiddleware($middleware);
        $this
            ->patch(["{$path}/{id}", 'name' => "{$path}.update"], "{$controller}@update")
            ->withMiddleware($middleware);
        $this
            ->delete(["{$path}/{id}", 'name' => "{$path}.delete"], "{$controller}@delete")
            ->withMiddleware($middleware);
    }
}
