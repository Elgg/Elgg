<?php

use Phinx\Migration\AbstractMigration;

class RemoveLegacyPasswordHashes extends AbstractMigration
{
	/**
	 * Remove legacy password hashes
	 */
	public function up() {

		if (!$this->hasTable('users_entity')) {
			return;
		}

		// remove legacy 2.x password and salt columns
		$table = $this->table('users_entity');

		if ($table->hasIndexByName('password')) {
			$table->removeIndexByName('password')->save();
		}

		if ($table->hasColumn('password')) {
			$table->removeColumn('password');
		}

		if ($table->hasColumn('salt')) {
			$table->removeColumn('salt');
		}
		
		$table->save();

	}

	public function down() {

		if (!$this->hasTable('users_entity')) {
			return;
		}

		$table = $this->table('users_entity');
		
		if (!$table->hasColumn('password')) {
			$table->addColumn('password', 'text', [
				'null' => false,
				'default' => '',
				'limit' => 32,
			]);
		}

		if (!$table->hasColumn('salt')) {
			$table->addColumn('salt', 'text', [
				'null' => false,
				'default' => '',
				'limit' => 8,
			]);
		}

		if (!$table->hasIndexByName('password')) {
			$table->addIndex(['password'], [
				'name' => 'password',
				'unique' => false,
			]);
		}

		$table->save();

	}
}
