<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAnnotationsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_annotations` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `entity_guid` bigint(20) unsigned NOT NULL,
	 * `name` text NOT NULL,
	 * `value` LONGTEXT NOT NULL,
	 * `value_type` enum('integer','text') NOT NULL,
	 * `owner_guid` bigint(20) unsigned NOT NULL,
	 * `access_id` int(11) NOT NULL,
	 * `time_created` int(11) NOT NULL,
	 * `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
	 * PRIMARY KEY (`id`),
	 * KEY `entity_guid` (`entity_guid`),
	 * KEY `name` (`name`(50)),
	 * KEY `value` (`value`(50)),
	 * KEY `owner_guid` (`owner_guid`),
	 * KEY `access_id` (`access_id`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("annotations")) {
			return;
		}

		$table = $this->table("annotations", [
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

		$table->addColumn('name', 'text', [
			'null' => false,
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addColumn('value_type', 'enum', [
			'null' => false,
			'limit' => 7,
			'values' => [
				'integer',
				'text'
			],
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

		$table->addColumn('time_created', 'integer', [
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

		$table->addIndex(['entity_guid'], [
			'name' => "entity_guid",
			'unique' => false
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
			'limit' => 50,
		]);

		$table->addIndex(['value'], [
			'name' => "value",
			'unique' => false,
			'limit' => 50,
		]);


		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);

		$table->save();

	}
}
