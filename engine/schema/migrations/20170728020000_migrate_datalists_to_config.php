<?php

use Elgg\Exceptions\Configuration\InstallationException;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class MigrateDatalistsToConfig extends AbstractMigration {

	/**
	 * Validates that there are no duplicate names in datalist and config tables
	 *
	 * @throws InstallationException
	 */
	public function validate() {

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$duplicates = $this->fetchAll("
			SELECT name
			FROM {$prefix}datalists
			WHERE name IN (SELECT name FROM {$prefix}config)
			AND name NOT IN ('processed_upgrades', 'version')
		");


		if (!empty($duplicates)) {
			$duplicates_array = [];
			foreach ($duplicates as $duplicate) {
				$duplicates_array[] = $duplicate['name'];
			}
			$duplicates = implode(', ', $duplicates_array);
			throw new InstallationException("Found names ({$duplicates}) in datalist that also exist in config. Don't know how to merge.");
		}

	}

	/**
	 * Migrates legacy 2.x datalists values to config table
	 */
	public function up() {

		if (!$this->hasTable('datalists') || !$this->hasTable('config')) {
			return;
		}

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$rows = $this->fetchAll("
			SELECT * FROM {$prefix}datalists
			WHERE name NOT IN ('version')
		");

		foreach ($rows as $row) {
			$value = $row['value'];
			if ($row['name'] !== 'processed_upgrades') {
				$value = serialize($row['value']);
			}

			$this->table('config')->insert([[
				'name' => $row['name'],
				'value' => $value,
			]])->saveData();
		}

		// all data migrated, so drop the table
		$this->table('datalists')->drop()->save();
	}

	/**
	 * Recreate datalists table
	 *
	 * @warning Note that the datalists values will not be populated back into the table
	 *          There is no way of telling which config values have come from datalists table
	 *
	 * CREATE TABLE `prefix_datalists` (
	 * `name` varchar(255) NOT NULL,
	 * `value` text NOT NULL,
	 * PRIMARY KEY (`name`)
	 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	 */
	public function down() {

		if ($this->hasTable("datalists")) {
			return;
		}

		$table = $this->table("datalists", [
			'id' => false,
			'primary_key' => ["name"],
			'engine' => "MyISAM",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
		]);

		$table->addColumn('value', 'text', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_REGULAR,
		]);

		$table->save();

	}
}
