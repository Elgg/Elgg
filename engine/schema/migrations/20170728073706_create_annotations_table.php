<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAnnotationsTable extends AbstractMigration {
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

		if ($this->hasTable("annotations")) {
			return;
		}

		$table = $this->table("annotations", [
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

		$table->addColumn('name', 'text', [
			'null' => false,
			'limit' => 65535,
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_LONG,
		]);

		$table->addColumn('value_type', 'enum', [
			'null' => false,
			'limit' => 7,
			'values' => [
				'integer',
				'text'
			],
		]);

		$table->addColumn('owner_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('access_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('time_created', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('enabled', 'enum', [
			'null' => false,
			'default' => 'yes',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);

		$table->addIndex(['entity_guid'], [
			'name' => "entity_guid",
			'unique' => false
		]);
		
		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
			'limit' => 50,
		]);

		$table->addIndex(['value'], [
			'name' => "value",
			'unique' => false,
			'limit' => 50,
		]);


		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);
		
		$table->save();

	}
}
