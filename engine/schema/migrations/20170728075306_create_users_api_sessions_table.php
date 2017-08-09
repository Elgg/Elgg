<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersApiSessionsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_users_apisessions` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `user_guid` bigint(20) unsigned NOT NULL,
	 * `token` varchar(40) DEFAULT NULL,
	 * `expires` int(11) NOT NULL,
	 * PRIMARY KEY (`id`),
	 * KEY `user_guid` (`user_guid`),
	 * KEY `token` (`token`)
	 * ) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("users_apisessions")) {
			return;
		}

		$table = $this->table("users_apisessions", [
			'engine' => "MEMORY",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('user_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('token', 'string', [
			'null' => true,
			'limit' => 40,
		]);

		$table->addColumn('expires', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
			'after' => 'token'
		]);

		$table->addIndex(['user_guid'], [
			'name' => "user_guid",
			'unique' => false,
		]);

		$table->addIndex(['token'], [
			'name' => "token",
			'unique' => false,
		]);

		$table->save();
	}
}
