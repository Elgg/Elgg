<?php

use Phinx\Migration\AbstractMigration;

class RemoveRiverEnabledColumn extends AbstractMigration
{
    /**
	 * Removes enabled from river table
	 */
	public function change() {

		if ($this->hasTable('river')) {
			$table = $this->table('river');

			if ($table->hasColumn('enabled')) {
				$table->removeColumn('enabled');
			}

			$table->save();
		}
	}
}
