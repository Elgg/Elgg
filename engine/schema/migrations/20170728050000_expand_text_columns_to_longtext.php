<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ExpandTextColumnsToLongtext extends AbstractMigration {

	// Columns that change from text to longtext
	private $text_to_longtext = [
		'annotations' => ['value'],
		'config' => ['value'],
		'groups_entity' => ['description'],
		'metadata' => ['value'],
		'objects_entity' => ['description'],
		'private_settings' => ['value'],
		'sites_entity' => ['description'],
	];

	/**
	 * Expand certain columns from text to longtext
	 */
	public function up() {

		foreach ($this->text_to_longtext as $table => $columns) {
			if (!$this->hasTable($table)) {
				continue;
			}

			$table = $this->table($table);

			foreach ($columns as $column) {
				if ($table->hasColumn($column)) {
					$table->changeColumn($column, 'text', [
						'limit' => MysqlAdapter::TEXT_LONG,
					]);
				}
			}

			$table->save();
		}
	}

	/**
	 * Shirnk certain columns from longtext to text
	 */
	public function down() {

		foreach ($this->text_to_longtext as $table => $columns) {
			if (!$this->hasTable($table)) {
				continue;
			}

			$table = $this->table($table);

			foreach ($columns as $column) {
				if ($table->hasColumn($column)) {
					$table->changeColumn($column, 'text', [
						'limit' => MysqlAdapter::TEXT_REGULAR,
					]);
				}
			}

			$table->save();
		}
	}
}
