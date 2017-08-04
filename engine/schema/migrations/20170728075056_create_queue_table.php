<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateQueueTable extends AbstractMigration {
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

		if ($this->hasTable("queue")) {
			return;
		}

		$table = $this->table("queue", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => 252,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('data', 'blob', [
			'null' => false,
			'limit' => MysqlAdapter::BLOB_MEDIUM,
		]);

		$table->addColumn('timestamp', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('worker', 'string', [
			'null' => true,
			'limit' => 32,
		]);
		
		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
		]);

		$table->addIndex([
			'timestamp',
			'worker'
		], [
			'name' => "retrieve",
			'unique' => false
		]);

		$table->save();
	}
}
