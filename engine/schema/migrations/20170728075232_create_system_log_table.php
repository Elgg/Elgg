<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateSystemLogTable extends AbstractMigration {
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

		if ($this->hasTable("system_log")) {
			return;
		}

		$table = $this->table("system_log", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('object_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('object_class', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('object_type', 'string', [
			'null' => false,
			'limit' => 50,
		]);
		
		$table->addColumn('object_subtype', 'string', [
			'null' => false,
			'limit' => 50,
		]);
		
		$table->addColumn('event', 'string', [
			'null' => false,
			'limit' => 50,
		]);
		
		$table->addColumn('performed_by_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
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
		
		$table->addColumn('enabled', 'enum', [
			'null' => false,
			'default' => 'yes',
			'limit' => 3,
			'values' => [
				'yes',
				'no'
			],
		]);
		
		$table->addColumn('time_created', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);
		
		$table->addColumn('ip_address', 'string', [
			'null' => false,
			'limit' => 46,
		]);

		$table->addIndex(['object_id'], [
			'name' => "object_id",
			'unique' => false,
		]);

		$table->addIndex(['object_class'], [
			'name' => "object_class",
			'unique' => false,
		]);

		$table->addIndex(['object_type'], [
			'name' => "object_type",
			'unique' => false,
		]);

		$table->addIndex(['object_subtype'], [
			'name' => "object_subtype",
			'unique' => false,
		]);

		$table->addIndex(['event'], [
			'name' => "event",
			'unique' => false,
		]);

		$table->addIndex(['performed_by_guid'], [
			'name' => "performed_by_guid",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);

		$table->addIndex(['time_created'], [
			'name' => "time_created",
			'unique' => false,
		]);

		$table->addIndex([
			'object_type',
			'object_subtype',
			'event'
		], [
			'name' => "river_key",
			'unique' => false
		]);
		
		$table->save();
		
	}
}
