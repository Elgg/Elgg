<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEntitiesTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_entities` (
	 * `guid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	 * `type` enum('object','user','group','site') NOT NULL,
	 * `subtype` int(11) DEFAULT NULL,
	 * `owner_guid` bigint(20) unsigned NOT NULL,
	 * `container_guid` bigint(20) unsigned NOT NULL,
	 * `access_id` int(11) NOT NULL,
	 * `time_created` int(11) NOT NULL,
	 * `time_updated` int(11) NOT NULL,
	 * `last_action` int(11) NOT NULL DEFAULT '0',
	 * `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
	 * PRIMARY KEY (`guid`),
	 * KEY `type` (`type`),
	 * KEY `subtype` (`subtype`),
	 * KEY `owner_guid` (`owner_guid`),
	 * KEY `container_guid` (`container_guid`),
	 * KEY `access_id` (`access_id`),
	 * KEY `time_created` (`time_created`),
	 * KEY `time_updated` (`time_updated`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("entities")) {
			return;
		}

		$table = $this->table("entities", [
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
			'identity' => 'enable',
		]);

		$table->addColumn('type', 'enum', [
			'null' => false,
			'limit' => 6,
			'values' => [
				'object',
				'user',
				'group',
				'site',
			],
		]);

		$table->addColumn('subtype', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,

		]);

		$table->addColumn('owner_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('container_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('access_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('time_created', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('time_updated', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('last_action', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('enabled', 'enum', [
			'null' => false,
			'default' => 'yes',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);

		$table->addIndex(['type'], [
			'name' => "type",
			'unique' => false,
		]);

		$table->addIndex(['subtype'], [
			'name' => "subtype",
			'unique' => false,
		]);

		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false,
		]);

		$table->addIndex(['container_guid'], [
			'name' => "container_guid",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);

		$table->addIndex(['time_created'], [
			'name' => "time_created",
			'unique' => false,
		]);

		$table->addIndex(['time_updated'], [
			'name' => "time_updated",
			'unique' => false,
		]);

		$table->save();

	}
}
