<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateRiverTable extends AbstractMigration {
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

		if ($this->hasTable("river")) {
			return;
		}

		$table = $this->table("river", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('type', 'string', [
			'null' => false,
			'limit' => 8,
		]);

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 32,
		]);

		$table->addColumn('action_type', 'string', [
			'null' => false,
			'limit' => 32,
		]);

		$table->addColumn('access_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('view', 'text', [
			'null' => false,
			'limit' => 65535,
		]);

		$table->addColumn('subject_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('object_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('target_guid', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('annotation_id', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
		]);

		$table->addColumn('posted', 'integer', [
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

		$table->addIndex(['type'], [
			'name' => "type",
			'unique' => false,
		]);
		
		$table->addIndex(['action_type'], [
			'name' => "action_type",
			'unique' => false,
		]);

		$table->addIndex(['access_id'], [
			'name' => "access_id",
			'unique' => false,
		]);

		$table->addIndex(['subject_guid'], [
			'name' => "subject_guid",
			'unique' => false,
		]);

		$table->addIndex(['object_guid'], [
			'name' => "object_guid",
			'unique' => false,
		]);

		$table->addIndex(['target_guid'], [
			'name' => "target_guid",
			'unique' => false,
		]);

		$table->addIndex(['annotation_id'], [
			'name' => "annotation_id",
			'unique' => false,
		]);

		$table->addIndex(['posted'], [
			'name' => "posted",
			'unique' => false,
		]);

		$table->save();

	}
}
