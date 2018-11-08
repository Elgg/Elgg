<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysToPrivateSettingsTable extends AbstractMigration {

	public function change() {
		$table = $this->table('private_settings');

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			DELETE FROM {$prefix}private_settings 
			WHERE entity_guid NOT IN (
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

		$table->update();
	}
}
