<?php

namespace Elgg\Mocks\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result as DriverResult;

class Result extends \Doctrine\DBAL\Result {
	
	public function __construct(?DriverResult $result = null, ?Connection $connection = null, protected array $results = [], protected int $row_count = 0) {
	
	}
	
	public function fetchAssociative(): array|false {
		$result = array_shift($this->results);
		return isset($result) ? (array) $result : false;
	}
	
	public function fetchAllAssociative(): array {
		return $this->results;
	}
	
	public function rowCount(): int|string {
		return $this->row_count;
	}
}
