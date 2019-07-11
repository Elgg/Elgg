<?php

use Phinx\Migration\AbstractMigration;

class AlignSubtypeColumns extends AbstractMigration {
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

		$options = [
			'null' => false,
			'limit' => 252,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		];

		$table = $this->table('river');
		$table->changeColumn('subtype', 'string', $options)->save();

		$table = $this->table('entities');
		$table->changeColumn('subtype', 'string', $options)->save();

		$table = $this->table('system_log');

		$table->removeIndexByName('river_key')->save();

		$table->changeColumn('object_subtype', 'string', $options)->save();

		$table->addIndex([
			'object_type',
			'object_subtype',
			'event'
		], [
			'name' => "river_key",
			'unique' => false,
			'limit' => 25,
		]);

		$table->save();
	}
}
