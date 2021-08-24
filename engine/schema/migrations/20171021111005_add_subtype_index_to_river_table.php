<?php

use Phinx\Migration\AbstractMigration;

class AddSubtypeIndexToRiverTable extends AbstractMigration {
	/**
	 * Adds 'subtype' as index to the river table
	 */
	public function change() {

		$table = $this->table('river');

		if (!$table->hasIndexByName('subtype')) {
			$table->addIndex(['subtype'], [
				'name' => "subtype",
				'unique' => false,
			]);

			$table->save();
		}
	}
}
