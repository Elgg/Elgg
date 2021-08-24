<?php

use Phinx\Migration\AbstractMigration;

class UpdateMetadataColumns extends AbstractMigration {
	/**
	 * Changes metadata owner_guid, access_id and enabled column usage to allow null values
	 * Also removes owner_guid and access_id index as they are no longer used
	 */
	public function up() {

		if ($this->hasTable('metadata')) {
			$table = $this->table('metadata');

			if ($table->hasIndexByName('owner_guid')) {
				$table->removeIndexByName('owner_guid')->save();
			}
			if ($table->hasIndexByName('access_id')) {
				$table->removeIndexByName('access_id')->save();
			}

			if ($table->hasColumn('owner_guid')) {
				$table->changeColumn('owner_guid', 'integer', ['null' => true]);
			}
			if ($table->hasColumn('access_id')) {
				$table->changeColumn('access_id', 'integer', ['null' => true]);
			}

			$table->save();
		}
	}
}
