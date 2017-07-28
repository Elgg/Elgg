<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersEntityTable extends AbstractMigration {
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
	 *
	 * The following commands can be used in this method and Phinx will
	 * automatically reverse them when rolling back:
	 *
	 *    createTable
	 *    renameTable
	 *    addColumn
	 *    renameColumn
	 *    addIndex
	 *    addForeignKey
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
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
			'limit' => 65535,
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
			'limit' => 65535,
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
			'precision' => 10,
		]);

		$table->addColumn('prev_last_action', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('last_login', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('prev_last_login', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
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
