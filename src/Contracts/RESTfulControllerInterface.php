<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

namespace Exts\ServiceGateway\Contracts;

use FastD\Http\ServerRequest;

interface RESTfulControllerInterface
{
    public function index(ServerRequest $request);

    public function show(ServerRequest $request);

    public function store(ServerRequest $request);

    public function update(ServerRequest $request);

    public function delete(ServerRequest $request);
}
