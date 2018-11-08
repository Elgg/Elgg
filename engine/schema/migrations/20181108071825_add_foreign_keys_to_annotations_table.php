<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddForeignKeysToAnnotationsTable extends AbstractMigration {

	public function change() {
		$table = $this->table('annotations');

		$table->changeColumn('owner_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->update();

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			UPDATE {$prefix}annotations
			SET owner_guid = NULL 
			WHERE owner_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}annotations  
			WHERE owner_guid IS NOT NULL AND owner_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			UPDATE {$prefix}annotations
			SET entity_guid = NULL 
			WHERE entity_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}annotations  
			WHERE entity_guid IS NOT NULL AND entity_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");
		
		$table->addForeignKey(
			'entity_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'owner_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
