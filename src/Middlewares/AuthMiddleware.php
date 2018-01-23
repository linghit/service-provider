<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-04
 */

namespace Exts\ServiceGateway\Middlewares;

use Exts\ServiceGateway\Consumer;
use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware extends Middleware
{

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        if ('/health-check' === $request->getUri()->getPath()) {
            return $next->process($request);
        }

        $id = null;
        foreach (['http_x_consumer_custom_id', 'x-consumer-custom-id', 'x_consumer_custom_id',] as $header) {
            $request->hasHeader($header) && $id = $request->getHeader($header)[0];
        }
        if (is_null($id)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        if (app()->has('consumer_builder')) {
            app()->add('gateway_consumer', new Consumer(app()->get('consumer_builder')->apply($id) ?: []));
        }

        return $next($request);
    }
}
