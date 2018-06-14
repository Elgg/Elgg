<?php

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
		'metadata',
		'metastrings',
		'private_settings',
		'queue',
		'river',
		'system_log',
		'users_remember_me_cookies',
		'users_sessions',
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

			$this->execute("
				ALTER TABLE {$prefix}{$table}
				ENGINE=InnoDB
			");
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

			$this->execute("
				ALTER TABLE {$prefix}{$table}
				ENGINE=MyISAM
			");
		}

	}
}
