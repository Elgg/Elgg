<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAccessCollectionsTable extends AbstractMigration {
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

		if ($this->hasTable("access_collections")) {
			return;
		}

		$table = $this->table("access_collections", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('name', 'text', [
			'null' => false,
			'limit' => 65535,
		]);

		$table->addColumn('owner_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false
		]);

		$table->save();

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$this->query("ALTER TABLE {$prefix}access_collections AUTO_INCREMENT=3");
	}
}
