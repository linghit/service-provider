<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

if (!function_exists('cache_middleware')) {
    function cache_middleware($key, $callback, $expire = null)
    {
        $item = cache()->getItem($key);
        if (!$item->isHit()) {
            if (false === $data = call_user_func($callback)) {
                return false;
            };
            $item->set($data);
            if (!is_null($expire)) {
                $item->expiresAfter($expire);
            }
            cache()->save($item);
        }

        return $item->get();
    }
}

if (!function_exists('gateway_consumer')) {
    /**
     * @return \Exts\ServiceGateway\Consumer
     */
    function gateway_consumer()
    {
        return app()->get('gateway_consumer');
    }
}
