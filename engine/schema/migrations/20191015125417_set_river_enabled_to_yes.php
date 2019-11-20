<?php

use Phinx\Migration\AbstractMigration;

class SetRiverEnabledToYes extends AbstractMigration {
    
	/**
	 * Set the enabled state to 'yes' for all river items
	 */
	public function up() {
		$prefix = $this->getAdapter()->getOption('table_prefix');
		
		$this->execute("
			UPDATE {$prefix}river SET enabled = 'yes'
			WHERE enabled = 'no'
		");
    }
}
