<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateSystemLogTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_system_log` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `object_id` int(11) NOT NULL,
	 * `object_class` varchar(50) NOT NULL,
	 * `object_type` varchar(50) NOT NULL,
	 * `object_subtype` varchar(50) NOT NULL,
	 * `event` varchar(50) NOT NULL,
	 * `performed_by_guid` bigint(20) unsigned NOT NULL,
	 * `owner_guid` bigint(20) unsigned NOT NULL,
	 * `access_id` int(11) NOT NULL,
	 * `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
	 * `time_created` int(11) NOT NULL,
	 * `ip_address` varchar(46) NOT NULL,
	 * PRIMARY KEY (`id`),
	 * KEY `object_id` (`object_id`),
	 * KEY `object_class` (`object_class`),
	 * KEY `object_type` (`object_type`),
	 * KEY `object_subtype` (`object_subtype`),
	 * KEY `event` (`event`),
	 * KEY `performed_by_guid` (`performed_by_guid`),
	 * KEY `access_id` (`access_id`),
	 * KEY `time_created` (`time_created`),
	 * KEY `river_key` (`object_type`,`object_subtype`,`event`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("system_log")) {
			return;
		}

		$table = $this->table("system_log", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('object_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('object_class', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('object_type', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('object_subtype', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('event', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('performed_by_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('owner_guid', 'integer', [
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

		$table->addColumn('enabled', 'enum', [
			'null' => false,
			'default' => 'yes',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);

		$table->addColumn('time_created', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('ip_address', 'string', [
			'null' => false,
			'limit' => 46,
		]);

		$table->addIndex(['object_id'], [
			'name' => "object_id",
			'unique' => false,
		]);

		$table->addIndex(['object_class'], [
			'name' => "object_class",
			'unique' => false,
		]);

		$table->addIndex(['object_type'], [
			'name' => "object_type",
			'unique' => false,
		]);

		$table->addIndex(['object_subtype'], [
			'name' => "object_subtype",
			'unique' => false,
		]);

		$table->addIndex(['event'], [
			'name' => "event",
			'unique' => false,
		]);

		$table->addIndex(['performed_by_guid'], [
			'name' => "performed_by_guid",
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

		$table->addIndex([
			'object_type',
			'object_subtype',
			'event'
		], [
			'name' => "river_key",
			'unique' => false
		]);

		$table->save();

	}
}
