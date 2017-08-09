<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGroupsEntityTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_groups_entity` (
	 * `guid` bigint(20) unsigned NOT NULL,
	 * `name` text NOT NULL,
	 * `description` LONGTEXT NOT NULL,
	 * PRIMARY KEY (`guid`),
	 * KEY `name` (`name`(50)),
	 * KEY `description` (`description`(50))
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("groups_entity")) {
			return;
		}

		$table = $this->table("groups_entity", [
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
			'signed' => false,
		]);

		$table->addColumn('name', 'text', [
			'null' => false,
		]);

		$table->addColumn('description', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
			'limit' => 50,
		]);

		$table->addIndex(['description'], [
			'name' => "description",
			'unique' => false,
			'limit' => 50
		]);

		$table->save();

	}
}
