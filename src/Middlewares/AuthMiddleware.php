<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-04
 */

namespace Exts\ServiceGateway\Middlewares;

use Exts\ServiceGateway\Contracts\ConsumerBuilderInterface;
use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use Exception;

class AuthMiddleware extends Middleware
{

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        $id = null;
        foreach (['http_x_consumer_custom_id', 'x-consumer-custom-id', 'x_consumer_custom_id',] as $header) {
            $request->hasHeader($header) && $id = $request->getHeader($header)[0];
        }
        if (is_null($id)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        if ($class = config()->get('gateway.consumer_builder')) {
            try {
                $reflection = new ReflectionClass($class);
                if ($reflection->isSubclassOf(ConsumerBuilderInterface::class)) {
                    gateway_consumer()->merge($reflection->newInstance()->apply($id));
                }
            } catch (Exception $exception) {
            } finally {
                gateway_consumer()->setIsExists();
            }
        }

        return $next($request);
    }
}
