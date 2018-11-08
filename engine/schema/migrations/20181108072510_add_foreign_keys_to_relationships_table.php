<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysToRelationshipsTable extends AbstractMigration {

	public function change() {
		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			DELETE FROM {$prefix}entity_relationships 
			WHERE guid_one NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			DELETE FROM {$prefix}entity_relationships 
			WHERE guid_two NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$table = $this->table('entity_relationships');

		$table->addForeignKey(
			'guid_one',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'guid_two',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
