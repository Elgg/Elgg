<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEntitiesTable extends AbstractMigration {
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

		if ($this->hasTable("entities")) {
			return;
		}

		$table = $this->table("entities", [
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
			'identity' => 'enable',
		]);

		$table->addColumn('type', 'enum', [
			'null' => false,
			'limit' => 6,
			'values' => [
				'object',
				'user',
				'group',
				'site',
			],
		]);

		$table->addColumn('subtype', 'integer', [
			'null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,

		]);

		$table->addColumn('owner_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('container_guid', 'integer', [
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

		$table->addColumn('time_updated', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('last_action', 'integer', [
			'null' => false,
			'default' => '0',
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

		$table->addIndex(['type'], [
			'name' => "type",
			'unique' => false,
		]);

		$table->addIndex(['subtype'], [
			'name' => "subtype",
			'unique' => false,
		]);

		$table->addIndex(['owner_guid'], [
			'name' => "owner_guid",
			'unique' => false,
		]);

		$table->addIndex(['container_guid'], [
			'name' => "container_guid",
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

		$table->addIndex(['time_updated'], [
			'name' => "time_updated",
			'unique' => false,
		]);

		$table->save();

	}
}
