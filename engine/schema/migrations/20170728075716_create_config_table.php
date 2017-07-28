<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateConfigTable extends AbstractMigration {
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

		if ($this->hasTable("config")) {
			return;
		}

		$table = $this->table("config", [
			'id' => false,
			'primary_key' => ["name"],
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

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->save();
	}
}
