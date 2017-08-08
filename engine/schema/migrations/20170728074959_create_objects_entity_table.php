<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateObjectsEntityTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_objects_entity` (
	 * `guid` bigint(20) unsigned NOT NULL,
	 * `title` text NOT NULL,
	 * `description` LONGTEXT NOT NULL,
	 * PRIMARY KEY (`guid`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("objects_entity")) {
			return;
		}

		$table = $this->table("objects_entity", [
			'id' => false,
			'primary_key' => ["guid"],
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
		]);

		$table->addColumn('title', 'text', [
			'null' => false,
		]);

		$table->addColumn('description', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->save();

	}
}
