<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddEntityGuidNameIndexToAnnotations extends AbstractMigration {
	
	/**
	 * Add an index to the annotations table for the columns entity_guid and name
	 */
	public function change() {
		if (!$this->hasTable('annotations')) {
			return;
		}
		
		$table = $this->table('annotations');
		if ($table->hasIndexByName('entity_guid_name')) {
			return;
		}
		
		$table->addIndex(['entity_guid', 'name'], [
			'name' => 'entity_guid_name',
			'unique' => false,
			'limit' => [
				'name' => MysqlAdapter::INT_TINY,
			],
		]);
		
		$table->save();
	}
}
