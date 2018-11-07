<?php

use Phinx\Migration\AbstractMigration;

class AddEntitiesSubtypeIndex extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change() {
		$table = $this->table('entities');
		if ($table->hasIndex('subtype')) {
			return;
		}
		
		$table->addIndex(['subtype'], [
			'name' => "subtype",
			'unique' => false,
			'limit' => 50,
		]);
		
		$table->save();
    }
}
