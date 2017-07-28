<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGroupsEntityTable extends AbstractMigration {
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

		if ($this->hasTable("groups_entity")) {
			return;
		}

		$table = $this->table("groups_entity", [
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

		$table->addColumn('description', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
			'limit' => 50,
		]);

		$table->addIndex(['description'], [
			'name' => "description",
			'unique' => false,
			'limit' => 50
		]);

		$table->save();

	}
}
