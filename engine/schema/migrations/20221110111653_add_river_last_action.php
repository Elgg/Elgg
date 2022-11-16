<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddRiverLastAction extends AbstractMigration {
	/**
	 * Adds the river last action column and fills it with the posted timestamp
	 */
	public function change(): void {
		$table = $this->table('river');
		if ($table->hasColumn('last_action')) {
			return;
		}
    	
		$table->addColumn('last_action', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);
		$table->addIndex(['last_action'], [
			'name' => 'last_action',
			'unique' => false,
		]);
		
		$table->update();
		
		// copy posted into last_action
		$prefix = $this->getAdapter()->getOption('table_prefix');
		
		$this->execute("UPDATE {$prefix}river SET last_action = posted");
    }
}
