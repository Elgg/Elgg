<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAccessCollectionMembershipTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_access_collection_membership` (
	 * `user_guid` bigint(20) unsigned NOT NULL,
	 * `access_collection_id` int(11) NOT NULL,
	 * PRIMARY KEY (`user_guid`,`access_collection_id`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("access_collection_membership")) {
			return;
		}

		$table = $this->table("access_collection_membership", [
			'id' => false,
			'primary_key' => [
				"user_guid",
				"access_collection_id"
			],
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('user_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('access_collection_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->save();

	}
}
