<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddColumnsToEntitiesAndAnnotationsTables extends AbstractMigration {
	/**
	 * Add the soft_delete and time_soft_deleted columns to the entities and annotations table
	 */
	public function change(): void {
		$this->updateEntitiesTable();
		$this->updateAnnotationsTable();
    }
	
	protected function updateEntitiesTable(): void {
		$table = $this->table('entities');
		if ($table->hasColumn('soft_deleted')) {
			return;
		}
		
		$table->addColumn('soft_deleted', 'enum', [
			'null' => false,
			'default' => 'no',
			'limit' => 3,
			'values' => [
				'yes',
				'no',
			],
		]);
		
		$table->addIndex(['soft_deleted'], [
			'name' => 'soft_deleted',
			'unique' => false,
		]);
		
		$table->addColumn('time_soft_deleted', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);
		
		$table->update();
	}
	
	protected function updateAnnotationsTable(): void {
		$table = $this->table('annotations');
		if ($table->hasColumn('soft_deleted')) {
			return;
		}
		
		$table->addColumn('soft_deleted', 'enum', [
			'null' => false,
			'default' => 'no',
			'limit' => 3,
			'values' => [
				'yes',
				'no',
			],
		]);
		
		$table->addIndex(['soft_deleted'], [
			'name' => 'soft_deleted',
			'unique' => false,
		]);
		
		$table->addColumn('time_soft_deleted', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);
		
		$table->update();
	}
}
