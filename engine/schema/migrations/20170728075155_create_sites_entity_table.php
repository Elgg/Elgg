<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateSitesEntityTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_sites_entity` (
	 * `guid` bigint(20) unsigned NOT NULL,
	 * `name` text NOT NULL,
	 * `description` LONGTEXT NOT NULL,
	 * `url` varchar(255) CHARACTER SET utf8 NOT NULL,
	 * PRIMARY KEY (`guid`),
	 * UNIQUE KEY `url` (`url`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("sites_entity")) {
			return;
		}

		$table = $this->table("sites_entity", [
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

		$table->addColumn('name', 'text', [
			'null' => false,
		]);

		$table->addColumn('description', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addColumn('url', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addIndex(['url'], [
			'name' => "url",
			'unique' => true,
		]);

		$table->save();
	}
}
