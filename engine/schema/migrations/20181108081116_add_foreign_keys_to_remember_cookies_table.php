<?php

use Phinx\Migration\AbstractMigration;

class AddForeignKeysToRememberCookiesTable extends AbstractMigration {

	public function change() {
		$prefix = $this->getAdapter()->getOption('table_prefix');

		$this->execute("
			DELETE FROM {$prefix}users_remember_me_cookies 
			WHERE guid NOT IN (
				SELECT guid FROM {$prefix}entities
			)
		");

		$table = $this->table('users_remember_me_cookies');

		$table->addForeignKey(
			'guid',
			$prefix . 'entities',
			'guid', [
				'update' => 'CASCADE',
				'delete' => 'CASCADE',
			]
		);

		$table->update();
	}
}
