<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ChangeTableEngine extends AbstractMigration {

	private $innodb_tables = [
		'access_collection_membership',
		'access_collections',
		'annotations',
		'api_users',
		'config',
		'entities',
		'entity_relationships',
		'entity_subtypes',
		'groups_entity',
		'metadata',
		'metastrings',
		'objects_entity',
		'private_settings',
		'queue',
		'river',
		'sites_entity',
		'system_log',
		'users_entity',
		'users_remember_me_cookies',
		'users_sessions',
	];

	private $full_text_indexes = [
		'groups_entity' => [
			'name_2' => [
				'name',
				'description'
			],
		],
		'objects_entity' => [
			'title' => [
				'title',
				'description'
			],
		],
		'sites_entity' => [
			'name' => [
				'name',
				'description',
				'url'
			],
		],
		'users_entity' => [
			'name' => ['name'],
			'name2' => [
				'name',
				'username'
			],
		],
	];

	/**
	 * Changes table engine to InnoDb
	 */
	public function up() {

		$prefix = $this->getAdapter()->getOption('table_prefix');

		foreach ($this->innodb_tables as $table) {
			if (!$this->hasTable($table)) {
				continue;
			}

			$table = $this->table($table);

			if (!empty($this->full_text_indexes[$table->getName()])) {
				$indexes = $this->full_text_indexes[$table->getName()];
				foreach ($indexes as $index => $columns) {
					try {
						$this->execute("
							ALTER TABLE {$prefix}{$table->getName()}
							DROP KEY `{$index}`
						");
					} catch (Exception $e) {
						// though schema defines them, some of these keys do not seem to exist
					}
				}
			}

			$result = $this->execute("
				ALTER TABLE {$prefix}{$table->getName()}
				ENGINE=InnoDB
			");

			$table->save();
		}
	}

	/**
	 * Changes table engine to MyISAM
	 */
	public function down() {

		$prefix = $this->getAdapter()->getOption('table_prefix');

		foreach ($this->innodb_tables as $table) {
			if (!$this->hasTable($table)) {
				continue;
			}

			$table = $this->table($table);

			$this->execute("
				ALTER TABLE {$prefix}{$table->getName()}
				ENGINE=MyISAM
			");

			if (!empty($this->full_text_indexes[$table->getName()])) {
				$indexes = $this->full_text_indexes[$table->getName()];
				foreach ($indexes as $index => $columns) {
					$columns = implode(',', array_map(function ($e) {
						return "'$e'";
					}, $columns));

					$this->execute("
						ALTER TABLE {$prefix}{$table->getName()}
						ADD FULLTEXT INDEX {$index} ($columns)
					");
				}
			}

			$table->save();
		}

	}
}
