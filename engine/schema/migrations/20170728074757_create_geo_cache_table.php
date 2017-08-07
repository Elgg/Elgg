<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGeoCacheTable extends AbstractMigration {
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

		if ($this->hasTable("geocode_cache")) {
			return;
		}

		$table = $this->table("geocode_cache", [
			'engine' => "MEMORY",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('location', 'string', [
			'null' => true,
			'limit' => 128,
		]);

		$table->addColumn('lat', 'string', [
			'null' => true,
			'limit' => 20,
		]);

		$table->addColumn('long', 'string', [
			'null' => true,
			'limit' => 20,
		]);

		$table->addIndex(['location'], [
			'name' => "location",
			'unique' => true
		]);

		$table->save();

	}
}
