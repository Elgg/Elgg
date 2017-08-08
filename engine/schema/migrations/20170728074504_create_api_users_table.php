<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateApiUsersTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_api_users` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `api_key` varchar(40) DEFAULT NULL,
	 * `secret` varchar(40) NOT NULL,
	 * `active` int(1) DEFAULT '1',
	 * PRIMARY KEY (`id`),
	 * UNIQUE KEY `api_key` (`api_key`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("api_users")) {
			return;
		}

		$table = $this->table("api_users", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('api_key', 'string', [
			'null' => true,
			'limit' => 40,
		]);

		$table->addColumn('secret', 'string', [
			'null' => false,
			'limit' => 40,
		]);

		$table->addColumn('active', 'integer', [
			'null' => true,
			'default' => '1',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addIndex(['api_key'], [
			'name' => "api_key",
			'unique' => true,
		]);

		$table->save();
	}
}
