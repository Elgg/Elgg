<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEntitySubtypesTable extends AbstractMigration {
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

		if ($this->hasTable("entity_subtypes")) {
			return;
		}

		$table = $this->table("entity_subtypes", [
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('type', 'enum', [
			'null' => false,
			'limit' => 6,
			'values' => [
				'object',
				'user',
				'group',
				'site'
			],
		]);

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('class', 'string', [
			'null' => false,
			'default' => '',
			'limit' => 255,
		]);

		$table->addIndex([
			'type',
			'subtype'
		], [
			'name' => "type",
			'unique' => true
		]);

		$table->save();
	}
}
