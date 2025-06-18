<?php

namespace Elgg\Upgrades;

use Elgg\Database\DbConfig;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Updates database charset to utf8mb4
 */
class AlterDatabaseToMultiByteCharset extends AsynchronousUpgrade {

	private $utf8mb4_tables = [
		// InnoDB
		'access_collection_membership',
		'access_collections',
		'annotations',
		'api_users',
		'config',
		'entities',
		'entity_relationships',
		'metadata',
		'private_settings',
		'queue',
		'river',
		'system_log',
		'users_remember_me_cookies',
		'users_sessions',
		// MEMORY
		'hmac_cache',
		'users_apisessions',
	];

	// Columns with utf8 encoding and utf8_general_ci collation
	// $table => [
	//   $column => $index
	// ]

	private $non_mb4_columns = [
		'config' => [
			'name' => [
				'primary' => true,
				'name' => 'name',
				'unique' => false,
			],
		],
		'entities' => [
			'subtype' => [
				'primary' => false,
				'name' => 'subtype',
				'unique' => false,
			],
		],
		'queue' => [
			'name' => [
				'primary' => false,
				'name' => "name",
				'unique' => false,
			],
		],
		'users_sessions' => [
			'session' => [
				'primary' => true,
				'name' => 'session',
				'unique' => false,
			],
		],
		'hmac_cache' => [
			'hmac' => [
				'primary' => true,
				'name' => 'hmac',
				'unique' => false,
			],
		],
		'system_log' => [
			'object_class' => [
				'primary' => false,
				'name' => 'object_class',
				'unique' => false,
			],
			'object_type' => [
				'primary' => false,
				'name' => 'object_type',
				'unique' => false,
			],
			'object_subtype' => [
				'primary' => false,
				'name' => 'object_subtype',
				'unique' => false,
			],
			'event' => [
				'primary' => false,
				'name' => 'event',
				'unique' => false,
			],
			'river_key' => [
				'primary' => false,
				'name' => 'river_key',
				'unique' => false,
				'columns' => ['object_type', 'object_subtype', 'event']
			],
		]
	];

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2017080900;
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped(): bool {

		$config = _elgg_services()->dbConfig->getConnectionConfig();
		$rows = _elgg_services()->db->getConnection(DbConfig::READ)->executeQuery("SHOW TABLE STATUS FROM `{$config['database']}`");

		$prefixed_table_names = array_map(function ($t) use ($config) {
			return "{$config['prefix']}{$t}";
		}, $this->utf8mb4_tables);

		foreach ($rows->fetchAllAssociative() as $row) {
			$row = (object) $row;
			if (in_array($row->Name, $prefixed_table_names) && $row->Collation !== 'utf8mb4_general_ci') {
				return false;
			}
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems(): int {
		return 1;
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {

		$config = _elgg_services()->dbConfig->getConnectionConfig();

		try {
			// check if we need to change a global variable
			$db_result = _elgg_services()->db->getConnection(DbConfig::READ)->executeQuery("SHOW GLOBAL VARIABLES LIKE 'innodb_large_prefix'");
			$rows = $db_result->fetchAllAssociative();
			
			if (empty($rows) || $rows[0]['Value'] === 'OFF') {
				// required to allow bigger index sizes required for utf8mb4
				_elgg_services()->db->getConnection(DbConfig::WRITE)->executeStatement("SET GLOBAL innodb_large_prefix = 'ON'");
			}
		} catch (\Exception $e) {
			// something went wrong, maybe database permissions, or version
			$result->addFailures();
			$result->addError("Failure to set 'innodb_large_prefix'. Ask your database administrator for more information.");
			$result->addError("Alternatively ask the database administrator to (temporarily) set 'innodb_large_prefix' to 'ON'.");
			$result->addError($e->getMessage());
			
			return $result;
		}
		
		try {
			// alter table structure
			$connection = _elgg_services()->db->getConnection(DbConfig::WRITE);
			$connection->executeStatement("
				ALTER DATABASE
    			`{$config['database']}`
    			CHARACTER SET = utf8mb4
    			COLLATE = utf8mb4_unicode_ci
			");

			foreach ($this->utf8mb4_tables as $table) {
				if (!empty($this->non_mb4_columns[$table])) {
					foreach ($this->non_mb4_columns[$table] as $column => $index) {
						if ($index) {
							if ($index['primary']) {
								$connection->executeStatement("
									ALTER TABLE {$config['prefix']}{$table}
									DROP PRIMARY KEY
								");
							} else {
								$connection->executeStatement("
									ALTER TABLE {$config['prefix']}{$table}
									DROP KEY {$index['name']}
								");
							}
						}
					}
				}

				$connection->executeStatement("
					ALTER TABLE {$config['prefix']}{$table}
					ROW_FORMAT=DYNAMIC
				");

				$connection->executeStatement("
					ALTER TABLE {$config['prefix']}{$table}
					CONVERT TO CHARACTER SET utf8mb4
					COLLATE utf8mb4_general_ci
				");

				if (!empty($this->non_mb4_columns[$table])) {
					foreach ($this->non_mb4_columns[$table] as $column => $index) {
						if (empty($index['columns'])) {
							// Alter table only if the key is not composite
							$connection->executeStatement("
								ALTER TABLE {$config['prefix']}{$table}
								MODIFY $column VARCHAR(255)
								CHARACTER SET utf8
								COLLATE utf8_unicode_ci
							");
						}

						if (!$index) {
							continue;
						}

						$sql = "ADD";
						if ($index['unique']) {
							$sql .= " UNIQUE ({$index['name']})";
						} else if ($index['primary']) {
							$sql .= " PRIMARY KEY ({$index['name']})";
						} else {
							$key_columns = elgg_extract('columns', $index, [$column]);
							$key_columns = implode(',', $key_columns);
							$sql .= " KEY {$index['name']} ($key_columns)";
						}

						$connection->executeStatement("
							ALTER TABLE {$config['prefix']}{$table}
							$sql
						");
					}
				}
			}
		} catch (\Exception $e) {
			$result->addFailures();
			$result->addError($e->getMessage());
			
			return $result;
		}

		$result->addSuccesses();

		return $result;
	}
}
