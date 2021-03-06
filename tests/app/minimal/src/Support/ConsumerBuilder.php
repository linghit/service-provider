<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-06
 */

namespace Support;

use Exts\ServiceGateway\Contracts\ConsumerBuilderInterface;

class ConsumerBuilder implements ConsumerBuilderInterface
{

    /**
     * @param integer $id
     * @return array
     */
    public function apply($id)
    {
        if (3 == $id) {
            return false;
        }
        return [
            'name' => 'hello',
            'id' => $id,
        ];
    }
}
