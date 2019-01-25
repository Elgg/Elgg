<?php

use Phinx\Migration\AbstractMigration;

class EntitiesAddTypeSubtypeIndex extends AbstractMigration {
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
		if ($table->hasIndex('type_subtype')) {
			return;
		}
		
		$table->addIndex(['type', 'subtype'], [
			'name' => "type_subtype",
			'unique' => false,
			'limit' => 50,
		]);
		
		$table->update();
    }
}
