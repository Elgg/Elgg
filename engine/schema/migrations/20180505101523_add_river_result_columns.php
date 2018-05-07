<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddRiverResultColumns extends AbstractMigration {
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

		$table = $this->table('river');

		if (!$table->hasColumn('result_id')) {
			$table->addColumn('result_id', 'integer', [
				'null' => false,
				'limit' => MysqlAdapter::INT_BIG,
				'precision' => 20,
				'signed' => false,
			]);
		}

		if (!$table->hasColumn('result_type')) {
			$table->addColumn('result_type', 'string', [
				'null' => false,
				'limit' => 252,
			]);
		}

		if (!$table->hasColumn('result_subtype')) {
			$table->addColumn('result_subtype', 'string', [
				'null' => false,
				'limit' => 252,
			]);
		}

		if (!$table->hasIndex('result_id')) {
			$table->addIndex(['result_id'], [
				'name' => "result_id",
				'unique' => false,
			]);
		}

		if (!$table->hasIndex('result_type')) {
			$table->addIndex(['result_type'], [
				'name' => "result_type",
				'unique' => false,
			]);
		}

		if (!$table->hasIndex('result_subtype')) {
			$table->addIndex(['result_subtype'], [
				'name' => "result_subtype",
				'unique' => false,
			]);
		}

		$table->save();
	}
}
