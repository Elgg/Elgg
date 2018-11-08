<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysToAccessCollectionsMembershipTable extends AbstractMigration {

	public function change() {
		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			DELETE FROM {$prefix}access_collection_membership 
			WHERE user_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$this->execute("
			DELETE FROM {$prefix}access_collection_membership 
			WHERE access_collection_id NOT IN (
				SELECT id FROM {$prefix}access_collections
			)
		");

		$table = $this->table('access_collection_membership');

		$table->addForeignKey(
			'user_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->addForeignKey(
			'access_collection_id',
			$prefix . 'access_collections',
			'id', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
