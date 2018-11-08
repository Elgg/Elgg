<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddForeignKeysToRiverTable extends AbstractMigration {

	public function change() {
		$table = $this->table('river');

		$table->changeColumn('subject_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->changeColumn('object_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->changeColumn('target_guid', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->changeColumn('annotation_id', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
			'signed' => true,
		]);

		$table->update();

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			UPDATE {$prefix}river
			SET subject_guid = NULL 
			WHERE subject_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}river  
			WHERE subject_guid IS NOT NULL AND subject_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			UPDATE {$prefix}river
			SET object_guid = NULL 
			WHERE object_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}river  
			WHERE object_guid IS NOT NULL AND object_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			UPDATE {$prefix}river
			SET target_guid = NULL 
			WHERE target_guid = 0
		");

		$this->execute("
			DELETE FROM {$prefix}river  
			WHERE target_guid IS NOT NULL AND target_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			UPDATE {$prefix}river
			SET annotation_id = NULL 
			WHERE annotation_id = 0
		");

		$this->execute("
			DELETE FROM {$prefix}river  
			WHERE annotation_id IS NOT NULL AND annotation_id NOT IN (
				SELECT id FROM {$prefix}annotations
			)
		");
		
		$table->addForeignKey(
			'subject_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'object_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'target_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'annotation_id',
			$prefix . 'annotations',
			'id', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
