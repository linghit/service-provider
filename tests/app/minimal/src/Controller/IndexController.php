<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

namespace Controller;

class IndexController
{

    public function welcome()
    {
        return json([
            'foo' => 'bar',
        ]);
    }
}
