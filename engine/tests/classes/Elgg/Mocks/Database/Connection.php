<?php

namespace Elgg\Mocks\Database;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Result;
use Elgg\Mocks\Database;

class Connection extends \Doctrine\DBAL\Connection {
	
	protected ?Database $database;
	
	public function setDatabase(Database $database): void {
		$this->database = $database;
	}
	
	public function executeQuery(string $sql, array $params = [], array $types = [], ?QueryCacheProfile $qcp = null,): Result {
		return $this->database->executeDatabaseQuery($sql, $params, $types, $qcp);
	}
	
	public function executeStatement(string $sql, array $params = [], array $types = []): int|string {
		return $this->database->executeDatabaseStatement($sql, $params, $types);
	}
	
	public function lastInsertId(): int|string {
		return $this->database->getLastInsertId();
	}
	
	public function quote(string $value): string {
		return "'" . $value . "''";
	}
}
