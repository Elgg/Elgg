<?php

use Phinx\Migration\AbstractMigration;

class EntitiesAddTypeSubtypeContainerAndOwnerIndexes extends AbstractMigration
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
		
		if (!$table->hasIndexByName('type_subtype_owner')) {
			$table->addIndex(['type', 'subtype', 'owner_guid'], [
				'name' => "type_subtype_owner",
				'unique' => false,
				'limit' => ['subtype' => 50],
			]);
		}

		if (!$table->hasIndexByName('type_subtype_container')) {
			$table->addIndex(['type', 'subtype', 'container_guid'], [
				'name' => "type_subtype_container",
				'unique' => false,
				'limit' => ['subtype' => 50],
			]);
		}
		
		$table->update();
    }
}
