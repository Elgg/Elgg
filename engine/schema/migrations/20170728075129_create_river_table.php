<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateRiverTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_river` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `type` varchar(8) NOT NULL,
	 * `subtype` varchar(32) NOT NULL,
	 * `action_type` varchar(32) NOT NULL,
	 * `access_id` int(11) NOT NULL,
	 * `view` text NOT NULL,
	 * `subject_guid` bigint(20) unsigned NOT NULL,
	 * `object_guid` bigint(20) unsigned NOT NULL,
	 * `target_guid` bigint(20) unsigned NOT NULL,
	 * `annotation_id` int(11) NOT NULL,
	 * `posted` int(11) NOT NULL,
	 * `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
	 * PRIMARY KEY (`id`),
	 * KEY `type` (`type`),
	 * KEY `action_type` (`action_type`),
	 * KEY `access_id` (`access_id`),
	 * KEY `subject_guid` (`subject_guid`),
	 * KEY `object_guid` (`object_guid`),
	 * KEY `target_guid` (`target_guid`),
	 * KEY `annotation_id` (`annotation_id`),
	 * KEY `posted` (`posted`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("river")) {
			return;
		}

		$table = $this->table("river", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('type', 'string', [
			'null' => false,
			'limit' => 8,
		]);

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 32,
		]);

		$table->addColumn('action_type', 'string', [
			'null' => false,
			'limit' => 32,
		]);

		$table->addColumn('access_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('view', 'text', [
			'null' => false,
		]);

		$table->addColumn('subject_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('object_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('target_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('annotation_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('posted', 'integer', [
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

		$table->addIndex(['type'], [
			'name' => "type",
			'unique' => false,
		]);

		$table->addIndex(['action_type'], [
			'name' => "action_type",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);

		$table->addIndex(['subject_guid'], [
			'name' => "subject_guid",
			'unique' => false,
		]);

		$table->addIndex(['object_guid'], [
			'name' => "object_guid",
			'unique' => false,
		]);

		$table->addIndex(['target_guid'], [
			'name' => "target_guid",
			'unique' => false,
		]);

		$table->addIndex(['annotation_id'], [
			'name' => "annotation_id",
			'unique' => false,
		]);

		$table->addIndex(['posted'], [
			'name' => "posted",
			'unique' => false,
		]);

		$table->save();

	}
}
