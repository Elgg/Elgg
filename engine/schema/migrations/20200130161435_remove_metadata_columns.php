<?php

use Phinx\Migration\AbstractMigration;

class RemoveMetadataColumns extends AbstractMigration
{
    /**
	 * Removes obsolete columns from metadata table
	 */
	public function change() {

		if ($this->hasTable('metadata')) {
			$table = $this->table('metadata');

			if ($table->hasColumn('access_id')) {
				$table->removeColumn('access_id');
			}

			if ($table->hasColumn('owner_guid')) {
				$table->removeColumn('owner_guid');
			}

			if ($table->hasColumn('enabled')) {
				$table->removeColumn('enabled');
			}

			$table->save();
		}
	}
}
