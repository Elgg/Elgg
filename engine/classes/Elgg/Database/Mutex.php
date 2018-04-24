<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Loggable;
use Psr\Log\LoggerInterface;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Provides database mutex that can be used to prevent race conditions
 * between two processes that affect the same data.
 *
 * @access private
 * @since 2.1.0
 */
class Mutex {

	use Loggable;

	/**
	 * @var Database
	 */
	private $db;

	/**
	 * @var \Elgg\Logger
	 */
	private $logger;

	/**
	 * Constructor
	 *
	 * @param Database        $db     Database
	 * @param LoggerInterface $logger Logger
	 */
	public function __construct(Database $db, LoggerInterface $logger) {
		$this->db = $db;
		$this->logger = $logger;
	}

	/**
	 * Creates a table {prefix}{$namespace}_lock that is used as a mutex.
	 *
	 * @param string $namespace Allows having separate locks for separate processes
	 * @return bool
	 */
	public function lock($namespace) {
		$this->assertNamespace($namespace);

		if (!$this->isLocked($namespace)) {
			// Lock it
			$this->db->insertData("CREATE TABLE {$this->db->prefix}{$namespace}_lock (id INT)");
			$this->logger->info("Locked mutex for $namespace");
			return true;
		}

		$this->logger->warning("Cannot lock mutex for {$namespace}: already locked.");
		return false;
	}

	/**
	 * Unlocks mutex
	 *
	 * @param string $namespace Namespace to use for the database table
	 * @return void
	 */
	public function unlock($namespace) {
		$this->assertNamespace($namespace);

		$this->db->deleteData("DROP TABLE {$this->db->prefix}{$namespace}_lock");
		$this->logger->notice("Mutex unlocked for $namespace.");
	}

	/**
	 * Checks if mutex is locked
	 *
	 * @param string $namespace Namespace to use for the database table
	 * @return bool
	 */
	public function isLocked($namespace) {
		$this->assertNamespace($namespace);

		return (bool) count($this->db->getData("SHOW TABLES LIKE '{$this->db->prefix}{$namespace}_lock'"));
	}

	/**
	 * Assert that the namespace contains only characters [A-Za-z]
	 *
	 * @param string $namespace Namespace to use for the database table
	 * @throws \InvalidParameterException
	 * @return void
	 */
	private function assertNamespace($namespace) {
		if (!ctype_alpha($namespace)) {
			throw new \InvalidParameterException("Mutex namespace can only have characters [A-Za-z].");
		}
	}
}
