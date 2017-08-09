<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersEntityTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_users_entity` (
	 * `guid` bigint(20) unsigned NOT NULL,
	 * `name` text NOT NULL,
	 * `username` varchar(128) NOT NULL DEFAULT '',
	 * -- 255 chars is recommended by PHP.net to hold future hash formats
	 * `password_hash` varchar(255) NOT NULL DEFAULT '',
	 * `email` text NOT NULL,
	 * `language` varchar(6) NOT NULL DEFAULT '',
	 * `banned` enum('yes','no') NOT NULL DEFAULT 'no',
	 * `admin` enum('yes','no') NOT NULL DEFAULT 'no',
	 * `last_action` int(11) NOT NULL DEFAULT '0',
	 * `prev_last_action` int(11) NOT NULL DEFAULT '0',
	 * `last_login` int(11) NOT NULL DEFAULT '0',
	 * `prev_last_login` int(11) NOT NULL DEFAULT '0',
	 * PRIMARY KEY (`guid`),
	 * UNIQUE KEY `username` (`username`),
	 * KEY `email` (`email`(50)),
	 * KEY `last_action` (`last_action`),
	 * KEY `last_login` (`last_login`),
	 * KEY `admin` (`admin`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("users_entity")) {
			return;
		}

		$table = $this->table("users_entity", [
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
		]);

		$table->addColumn('name', 'text', [
			'null' => false,
		]);

		$table->addColumn('username', 'string', [
			'null' => false,
			'default' => '',
			'limit' => 128,
		]);

		$table->addColumn('password_hash', 'string', [
			'null' => false,
			'default' => '',
			'limit' => 255,
		]);

		$table->addColumn('email', 'text', [
			'null' => false,
		]);

		$table->addColumn('language', 'string', [
			'null' => false,
			'default' => '',
			'limit' => 6,
		]);

		$table->addColumn('banned', 'enum', [
			'null' => false,
			'default' => 'no',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);

		$table->addColumn('admin', 'enum', [
			'null' => false,
			'default' => 'no',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);

		$table->addColumn('last_action', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('prev_last_action', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('last_login', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('prev_last_login', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addIndex(['username'], [
			'name' => "username",
			'unique' => true,
		]);

		$table->addIndex(['email'], [
			'name' => "email",
			'unique' => false,
			'limit' => 50,
		]);

		$table->addIndex(['last_login'], [
			'name' => "last_login",
			'unique' => false,
		]);

		$table->addIndex(['admin'], [
			'name' => "admin",
			'unique' => false
		]);

		$table->save();
	}
}
