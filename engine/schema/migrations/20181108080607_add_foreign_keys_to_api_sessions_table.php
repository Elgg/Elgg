<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysToApiSessionsTable extends AbstractMigration {

	public function change() {
		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			DELETE FROM {$prefix}users_apisessions 
			WHERE user_guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$table = $this->table('users_apisessions');

		$table->addForeignKey(
			'user_guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
