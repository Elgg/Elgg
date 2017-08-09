<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePrivateSettingsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_private_settings` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `entity_guid` bigint(20) unsigned NOT NULL,
	 * `name` varchar(128) NOT NULL,
	 * `value` LONGTEXT NOT NULL,
	 * PRIMARY KEY (`id`),
	 * UNIQUE KEY `entity_guid` (`entity_guid`,`name`),
	 * KEY `name` (`name`),
	 * KEY `value` (`value`(50))
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("private_settings")) {
			return;
		}

		$table = $this->table("private_settings", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('entity_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => 128,
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addIndex([
			'entity_guid',
			'name'
		], [
			'name' => "entity_guid",
			'unique' => true,
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
		]);

		$table->addIndex(['value'], [
			'name' => "value",
			'unique' => false,
			'limit' => 50,
		]);

		$table->save();
	}
}
