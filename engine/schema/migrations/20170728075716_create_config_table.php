<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateConfigTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_config` (
	 * `name` varchar(255) CHARACTER SET utf8 NOT NULL,
	 * `value` LONGTEXT NOT NULL,
	 * PRIMARY KEY (`name`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("config")) {
			return;
		}

		$table = $this->table("config", [
			'id' => false,
			'primary_key' => ["name"],
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->save();
	}
}
