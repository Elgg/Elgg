<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

/**
 * Removes multisite support from 2.x schema
 */
class RemoveSiteGuid extends AbstractMigration {

	/**
	 * Ensure that legacy schema only has 1 site entity
	 * Refuse to upgrade if it doesn't
	 *
	 * @throws InstallationException
	 */
	public function validate() {

		// validate if multiple sites are in the database
		$tables = [
			'access_collections',
			'api_users',
			'config',
			'entities',
			'users_apisessions'
		];

		foreach ($tables as $table) {
			if (!$this->hasTable($table)) {
				continue;
			}

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$row = $this->fetchRow("
				SELECT count(DISTINCT site_guid) as count
				FROM {$prefix}{$table}
			");

			if ($row && $row['count'] > 1) {
				throw new InstallationException("Multiple sites detected in table: '{$prefix}{$table}'. Can't upgrade the database.");
			}
		}
	}

	/**
	 * Removes site guid from legacy 2.x tables
	 */
	public function up() {

		$this->validate();

		if ($this->hasTable('access_collections')) {
			$table = $this->table('access_collections');

			if ($table->hasIndex('site_guid')) {
				$table->removeIndexByName('site_guid');
			}

			if ($table->hasColumn('site_guid')) {
				$table->removeColumn('site_guid');
			}

			$table->save();
		}

		if ($this->hasTable('api_users')) {
			$table = $this->table('api_users');

			if ($table->hasColumn('site_guid')) {
				$table->removeColumn('site_guid');
			}

			$table->save();
		}

		if ($this->hasTable('config')) {
			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("ALTER TABLE {$prefix}config DROP PRIMARY KEY, ADD PRIMARY KEY(name)");
			$table = $this->table('config');

			if ($table->hasIndex('site_guid')) {
				$table->removeIndexByName('site_guid');
			}

			if ($table->hasColumn('site_guid')) {
				$table->removeColumn('site_guid');
			}

			$table->save();
		}

		if ($this->hasTable('entities')) {
			$table = $this->table('entities');

			if ($table->hasIndex('site_guid')) {
				$table->removeIndexByName('site_guid');
			}

			if ($table->hasColumn('site_guid')) {
				$table->removeColumn('site_guid');
			}

			$table->save();
		}

		if ($this->hasTable('users_apisessions')) {
			$table = $this->table('users_apisessions');

			if ($table->hasIndex('site_guid')) {
				$table->removeIndexByName('site_guid');
			}

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("ALTER TABLE {$prefix}users_apisessions DROP KEY user_guid, ADD UNIQUE KEY user_guid(user_guid)");

			if ($table->hasColumn('site_guid')) {
				$table->removeColumn('site_guid');
			}

			if ($table->hasIndex('user_guid')) {
				$table->removeIndex('user_guid');
			}

			$table->addIndex(['user_guid'], [
				'name' => "user_guid",
				'unique' => false,
			]);

			$table->save();
		}

		if ($this->hasTable('entity_relationships')) {
			// Remove member_of_site relaitonship following site_guid removal
			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				DELETE FROM {$prefix}entity_relationships
				WHERE relationship = 'member_of_site'
			");
		}

	}

	/**
	 * Add site_guid column and index
	 */
	public function down() {

		if ($this->hasTable('access_collections')) {
			$table = $this->table('access_collections');

			if (!$table->hasColumn('site_guid')) {
				$table->addColumn('site_guid', 'integer', [
					'null' => false,
					'limit' => MysqlAdapter::INT_BIG,
					'precision' => 20,
					'signed' => false,
				]);
			}

			if (!$table->hasIndex('site_guid')) {
				$table->addIndex(['site_guid'], [
					'name' => 'site_guid',
					'unique' => false,
				]);
			}

			$table->save();

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				UPDATE {$prefix}access_collections
				SET site_guid = 1
				WHERE site_guid != 1
			");
		}

		if ($this->hasTable('api_users')) {
			$table = $this->table('api_users');

			if (!$table->hasColumn('site_guid')) {
				$table->addColumn('site_guid', 'integer', [
					'null' => false,
					'limit' => MysqlAdapter::INT_BIG,
					'precision' => 20,
					'signed' => false,
				]);
			}

			$table->save();

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				UPDATE {$prefix}api_users
				SET site_guid = 1
				WHERE site_guid != 1
			");
		}

		if ($this->hasTable('config')) {
			$table = $this->table('config', [
				'primary_key' => [
					"name",
					"site_guid"
				],
			]);

			if (!$table->hasColumn('site_guid')) {
				$table->addColumn('site_guid', 'integer', [
					'null' => false,
					'limit' => MysqlAdapter::INT_BIG,
					'precision' => 20,
					'signed' => false,
				]);
			}

			if (!$table->hasIndex('site_guid')) {
				$table->addIndex(['site_guid'], [
					'name' => 'site_guid',
					'unique' => false,
				]);
			}

			$table->save();

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				UPDATE {$prefix}config
				SET site_guid = 1
				WHERE site_guid != 1
			");
		}

		if ($this->hasTable('entities')) {
			// remove site guid from entities
			$table = $this->table('entities');

			if (!$table->hasColumn('site_guid')) {
				$table->addColumn('site_guid', 'integer', [
					'null' => false,
					'limit' => MysqlAdapter::INT_BIG,
					'precision' => 20,
					'signed' => false,
				]);
			}

			if (!$table->hasIndex('site_guid')) {
				$table->addIndex(['site_guid'], [
					'name' => 'site_guid',
					'unique' => false,
				]);
			}

			$table->save();

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				UPDATE {$prefix}entities
				SET site_guid = 1
				WHERE site_guid != 1
			");

			if ($this->hasTable('entity_relationships')) {
				$rows = $this->fetchAll("
					SELECT guid FROM {$prefix}entities
					WHERE type = 'user'
				");

				foreach ($rows as $row) {
					$this->insert('entity_relationships', [
						'guid_one' => $row['guid'],
						'relationship' => 'member_of_site',
						'guid_two' => 1,
						'time_created' => time(),
					]);
				}
			}
		}

		if ($this->hasTable('users_apisessions')) {
			// remove site guid from users_apisessions
			$table = $this->table('users_apisessions');

			if ($table->hasIndex('site_guid')) {
				$table->removeIndexByName('site_guid');
			}

			if (!$table->hasColumn('site_guid')) {
				$table->addColumn('site_guid', 'integer', [
					'null' => false,
					'limit' => MysqlAdapter::INT_BIG,
					'precision' => 20,
					'signed' => false,
				]);
			}

			if ($table->hasIndex('user_guid')) {
				$table->removeIndexByName('user_guid');
			}

			$table->addIndex([
				'user_guid',
				'site_guid'
			], [
				'name' => "user_guid",
				'unique' => true,
			]);

			$table->save();

			$prefix = $this->getAdapter()->getOption('table_prefix');
			$this->query("
				UPDATE {$prefix}users_apisessions
				SET site_guid = 1
				WHERE site_guid != 1
			");
		}

	}
}
