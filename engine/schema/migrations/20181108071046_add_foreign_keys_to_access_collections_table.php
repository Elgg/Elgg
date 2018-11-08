<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddForeignKeysToAccessCollectionsTable extends AbstractMigration {

	public function change() {
		$table = $this->table('access_collections');

		$table->changeColumn('owner_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->update();

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			UPDATE {$prefix}access_collections
			SET owner_guid = NULL 
			WHERE owner_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}access_collections  
			WHERE owner_guid IS NOT NULL AND owner_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

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
