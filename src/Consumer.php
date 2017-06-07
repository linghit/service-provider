<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

namespace Exts\ServiceGateway;

use FastD\Utils\ArrayObject;

class Consumer extends ArrayObject
{

    protected $exists = false;

    public function setIsExists()
    {
        $this->exists = true;

        return $this;
    }

    public function exists()
    {
        return $this->exists;
    }
}
