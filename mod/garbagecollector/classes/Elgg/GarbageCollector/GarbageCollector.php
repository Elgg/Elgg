<?php

namespace Elgg\GarbageCollector;

use Doctrine\DBAL\Result;
use Elgg\Application\Database;
use Elgg\I18n\Translator;
use Elgg\Traits\Di\ServiceFacade;

/**
 * Garbage collecting service
 */
class GarbageCollector {

	use ServiceFacade;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var array
	 */
	protected $tables;

	/**
	 * Constructor
	 *
	 * @param Database   $db         Database
	 * @param Translator $translator Translator
	 */
	public function __construct(Database $db, Translator $translator) {
		$this->db = $db;
		$this->translator = $translator;
	}

	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'garbagecollector';
	}

	/**
	 * Optimize the database
	 *
	 * @return \stdClass[]
	 */
	public function optimize(): array {
		$dbprefix = $this->db->prefix;
		$output = [];

		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:start'),
			'result' => true,
			'completed' => new \DateTime(),
		];

		foreach ($this->tables() as $table) {
			if (stripos($table, "{$dbprefix}system_log_") === 0) {
				// rotated system_log tables don't need to be optimized
				continue;
			}

			$result = $this->optimizeTable($table) !== false;
			$output[] = (object) [
				'operation' => $this->translator->translate('garbagecollector:optimize', [$table]),
				'result' => $result,
				'completed' => new \DateTime(),
			];
		}

		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:done'),
			'result' => true,
			'completed' => new \DateTime(),
		];

		return $output;
	}

	/**
	 * Get a list of DB tables
	 *
	 * @return array
	 */
	protected function tables(): array {

		if (!isset($this->tables)) {
			$table_prefix = $this->db->prefix;
			$result = $this->db->getConnection('read')->executeQuery("SHOW TABLES LIKE '{$table_prefix}%'");

			$tables = [];

			if ($result instanceof Result) {
				$rows = $result->fetchAllAssociative();
				foreach ($rows as $row) {
					if (empty($row)) {
						continue;
					}

					foreach ($row as $element) {
						$tables[] = $element;
					}
				}
			}

			$this->tables = $tables;
		}

		return $this->tables;
	}

	/**
	 * Optimize table
	 *
	 * @param string $table Table
	 *
	 * @return int
	 */
	protected function optimizeTable(string $table): int {
		$result = $this->db->getConnection('write')->executeQuery("OPTIMIZE TABLE {$table}");
		return $result->rowCount();
	}
}
