<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class UpdateSystemLog extends AbstractMigration {

	/**
	 * Increases length for systemlog columns
	 *
	 * @return void
	 */
	public function change(): void {

		if ($this->hasTable('system_log')) {
			$table = $this->table('system_log');

			$table->changeColumn('object_class', 'string', ['limit' => MysqlAdapter::TEXT_SMALL]);
			$table->changeColumn('event', 'string', ['limit' => MysqlAdapter::TEXT_SMALL]);

			$table->save();
		}
	}
}
