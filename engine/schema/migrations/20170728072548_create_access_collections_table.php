<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAccessCollectionsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_access_collections` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `name` text NOT NULL,
	 * `owner_guid` bigint(20) unsigned NOT NULL,
	 * PRIMARY KEY (`id`),
	 * KEY `owner_guid` (`owner_guid`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("access_collections")) {
			return;
		}

		$table = $this->table("access_collections", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('name', 'text', [
			'null' => false,
		]);

		$table->addColumn('owner_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false
		]);

		$table->save();

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$this->query("ALTER TABLE {$prefix}access_collections AUTO_INCREMENT=3");
	}
}
