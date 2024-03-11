<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddColumnsToEntitiesAndAnnotationsTables extends AbstractMigration {
	/**
	 * Adds the river last action column and fills it with the posted timestamp
	 */
	public function change(): void {
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
                'no'
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
		
//		// copy posted into last_action
//		$prefix = $this->getAdapter()->getOption('table_prefix');
//
//		$this->execute("UPDATE {$prefix}entities SET time_soft_deleted = posted");


        $table_annotations = $this->table('annotations');
        if ($table_annotations->hasColumn('soft_deleted')) {
            return;
        }

        $table_annotations->addColumn('soft_deleted', 'enum', [
            'null' => false,
            'default' => 'no',
            'limit' => 3,
            'values' => [
                'yes',
                'no'
            ],
        ]);

        $table_annotations->addIndex(['soft_deleted'], [
            'name' => 'soft_deleted',
            'unique' => false,
        ]);

        $table_annotations->addColumn('time_soft_deleted', 'integer', [
            'null' => false,
            'default' => '0',
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 11,
        ]);

        $table_annotations->update();

    }
}
