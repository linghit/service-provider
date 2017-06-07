<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

use Exts\ServiceGateway\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Router
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Router();
        $this->collection->resource('users', 'UsersController');
    }

    public function testRouteName()
    {
        $this->assertEquals('users.show', $this->collection->getRoute('users.show')->getName());
        $this->assertEquals('users.index', $this->collection->getRoute('users.index')->getName());
        $this->assertEquals('users.update', $this->collection->getRoute('users.update')->getName());
        $this->assertEquals('users.store', $this->collection->getRoute('users.store')->getName());
        $this->assertEquals('users.delete', $this->collection->getRoute('users.delete')->getName());
    }

    public function testRouteMethod()
    {
        $this->assertEquals('GET', $this->collection->getRoute('users.index')->getMethod());
        $this->assertEquals('GET', $this->collection->getRoute('users.show')->getMethod());
        $this->assertEquals('POST', $this->collection->getRoute('users.store')->getMethod());
        $this->assertEquals('PATCH', $this->collection->getRoute('users.update')->getMethod());
        $this->assertEquals('DELETE', $this->collection->getRoute('users.delete')->getMethod());
    }

}
