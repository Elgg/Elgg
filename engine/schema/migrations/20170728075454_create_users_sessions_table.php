<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersSessionsTable extends AbstractMigration {
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
			'limit' => 252,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('ts', 'integer', [
			'null' => false,
			'default' => '0',
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
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
