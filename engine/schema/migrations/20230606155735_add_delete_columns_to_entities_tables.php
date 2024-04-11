<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddDeleteColumnsToEntitiesTables extends AbstractMigration {
	/**
	 * Add the deleted and time_deleted columns to the entities table
	 */
	public function change(): void {
		$table = $this->table('entities');
		if (!$table->hasColumn('deleted')) {
			$table->addColumn('deleted', 'enum', [
				'null' => false,
				'default' => 'no',
				'limit' => 3,
				'values' => [
					'yes',
					'no',
				],
			]);
			
			$table->addIndex(['deleted'], [
				'name' => 'deleted',
				'unique' => false,
			]);
		}
		
		if (!$table->hasColumn('time_deleted')) {
			$table->addColumn('time_deleted', 'integer', [
				'null' => false,
				'default' => '0',
				'limit' => MysqlAdapter::INT_REGULAR,
				'precision' => 11,
			]);
			
			$table->addIndex(['time_deleted'], [
				'name' => 'time_deleted',
				'unique' => false,
			]);
		}
		
		$table->update();
    }
}
