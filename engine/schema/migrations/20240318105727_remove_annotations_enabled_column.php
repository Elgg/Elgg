<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class RemoveAnnotationsEnabledColumn extends AbstractMigration {
	/**
	 * Removes enabled column from annotations
	 */
	public function change(): void {

		if ($this->hasTable('annotations')) {
			$table = $this->table('annotations');
			
			if ($table->hasColumn('enabled')) {
				$table->removeColumn('enabled');
			}
			
			$table->save();
		}
	}
}
