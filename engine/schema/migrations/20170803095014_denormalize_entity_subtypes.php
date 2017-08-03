<?php

use Phinx\Migration\AbstractMigration;

class DenormalizeEntitySubtypes extends AbstractMigration {
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

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$table = $this->table('entities');

		$table->renameColumn('subtype', 'subtype_id');
		$table->save();

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 50,
			'after' => 'type',
		]);
		$table->save();

		$this->query(
			"
			UPDATE {$prefix}entities e
			JOIN {$prefix}entity_subtypes es ON e.subtype_id = es.id
			SET e.subtype = es.subtype
			"
		);

		foreach (['user', 'group', 'site'] as $type) {
			$this->query(
				"
				UPDATE {$prefix}entities e
				SET e.subtype = '{$type}'
				WHERE e.type = '{$type}' AND e.subtype_id = 0
				"
			);
		}

		$table->removeColumn('subtype_id');

		$this->dropTable('entity_subtypes');
	}
}
