<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersRememberMeCookiesTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_users_remember_me_cookies` (
	 * `code` varchar(32) NOT NULL,
	 * `guid` bigint(20) unsigned NOT NULL,
	 * `timestamp` int(11) unsigned NOT NULL,
	 * PRIMARY KEY (`code`),
	 * KEY `timestamp` (`timestamp`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("users_remember_me_cookies")) {
			return;
		}

		$table = $this->table("users_remember_me_cookies", [
			'id' => false,
			'primary_key' => ["code"],
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('code', 'string', [
			'null' => false,
			'limit' => 32,
		]);

		$table->addColumn('guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('timestamp', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
			'signed' => false,
		]);

		$table->addIndex(['timestamp'], [
			'name' => "timestamp",
			'unique' => false,
		]);

		$table->save();
	}
}
