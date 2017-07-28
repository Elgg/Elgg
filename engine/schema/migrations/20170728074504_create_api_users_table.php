<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateApiUsersTable extends AbstractMigration {
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
			'precision' => 10,
		]);

		$table->addIndex(['api_key'], [
			'name' => "api_key",
			'unique' => true,
		]);

		$table->save();
	}
}
