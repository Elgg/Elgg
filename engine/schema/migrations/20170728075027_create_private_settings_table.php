<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePrivateSettingsTable extends AbstractMigration {
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

		if ($this->hasTable("private_settings")) {
			return;
		}

		$table = $this->table("private_settings", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('entity_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => 128,
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addIndex([
			'entity_guid',
			'name'
		], [
			'name' => "entity_guid",
			'unique' => true,
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
		]);

		$table->addIndex(['value'], [
			'name' => "value",
			'unique' => false,
			'limit' => 50,
		]);

		$table->save();
	}
}
