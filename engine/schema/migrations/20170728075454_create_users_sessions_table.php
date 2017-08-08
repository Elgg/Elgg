<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersSessionsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_users_sessions` (
	 * `session` varchar(255) CHARACTER SET utf8 NOT NULL,
	 * `ts` int(11) unsigned NOT NULL DEFAULT '0',
	 * `data` mediumblob,
	 * PRIMARY KEY (`session`),
	 * KEY `ts` (`ts`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("users_sessions")) {
			return;
		}

		$table = $this->table("users_sessions", [
			'id' => false,
			'primary_key' => ["session"],
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('session', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('ts', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
			'signed' => false,
		]);

		$table->addColumn('data', 'blob', [
			'null' => true,
			'limit' => MysqlAdapter::BLOB_MEDIUM,
		]);

		$table->addIndex(['ts'], [
			'name' => "ts",
			'unique' => false,
		]);

		$table->save();

	}
}
