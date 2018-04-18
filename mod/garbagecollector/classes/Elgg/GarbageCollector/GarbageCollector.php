<?php

namespace Elgg\GarbageCollector;

use function DI\object;
use Elgg\Application\Database;
use Elgg\Di\ServiceFacade;
use Elgg\I18n\Translator;

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
	 * @return string
	 */
	public static function name() {
		return 'garbagecollector';
	}

	/**
	 * Optimize the database
	 *
	 * @return \stdClass[]
	 * @throws \DatabaseException
	 */
	public function optimize() {
		$output = [];

		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:start'),
			'result' => true,
			'completed' => new \DateTime(),
		];

		foreach ($this->tables() as $table) {
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
	 * @throws \DatabaseException
	 */
	public function tables() {

		if (!isset($this->tables)) {
			$table_prefix = $this->db->prefix;
			$result = $this->db->getData("SHOW TABLES LIKE '$table_prefix%'");

			$tables = [];

			if (is_array($result) && !empty($result)) {
				foreach ($result as $row) {
					$row = (array) $row;
					if (is_array($row) && !empty($row)) {
						foreach ($row as $element) {
							$tables[] = $element;
						}
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
	 * @return bool|int
	 * @throws \DatabaseException
	 */
	public function optimizeTable($table) {
		$table = $this->db->sanitizeString($table);

		return $this->db->updateData("OPTIMIZE TABLE $table");
	}

}