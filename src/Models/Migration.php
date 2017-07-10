<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-04
 */
namespace Exts\ServiceGateway\Models;

use FastD\Model\Migration as BaseMigration;
use Phinx\Db\Table;

abstract class Migration extends BaseMigration
{

    public function change()
    {
        $table = $this->setUp();

        !$table->exists() && $table->create();

        $this->dataSet($table);
    }

    public function dataSet(Table $table)
    {
    }
}
