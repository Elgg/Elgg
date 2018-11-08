<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddForeignKeysToEntitiesTable extends AbstractMigration {

	public function change() {
		$table = $this->table('entities');

		$table->changeColumn('owner_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->changeColumn('container_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->update();

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			UPDATE {$prefix}entities e
			SET e.owner_guid = NULL 
			WHERE e.owner_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}entities 
			WHERE owner_guid IS NOT NULL 
			AND owner_guid NOT IN ( 
				SELECT eg.guid 
				FROM (SELECT * FROM {$prefix}entities) AS eg 
			)
		");

		$this->execute("
			UPDATE {$prefix}entities e
			SET e.container_guid = NULL 
			WHERE e.container_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}entities 
			WHERE container_guid IS NOT NULL 
			AND container_guid NOT IN ( 
				SELECT eg.guid 
				FROM (SELECT * FROM {$prefix}entities) AS eg 
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

		$table->addForeignKey(
			'container_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
