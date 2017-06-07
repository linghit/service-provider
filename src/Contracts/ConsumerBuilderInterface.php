<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

namespace Exts\ServiceGateway\Contracts;

interface ConsumerBuilderInterface
{
    /**
     * @param integer $id
     * @return array
     */
    public function apply($id);
}
