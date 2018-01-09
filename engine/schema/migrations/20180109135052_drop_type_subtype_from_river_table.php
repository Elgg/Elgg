<?php

use Phinx\Migration\AbstractMigration;

class DropTypeSubtypeFromRiverTable extends AbstractMigration {
	/**
	 * Drops type and subtype columns from the river table
	 */
	public function change() {

		if ($this->hasTable('river')) {
			$table = $this->table('river');

			if ($table->hasColumn('type')) {
				$table->removeColumn('type');
			}

			if ($table->hasColumn('subtype')) {
				$table->removeColumn('subtype');
			}

			if ($table->hasColumn('access_id')) {
				$table->removeColumn('access_id');
			}

			$table->save();
		}
	}
}
