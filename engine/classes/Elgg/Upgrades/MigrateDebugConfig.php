<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;
use Psr\Log\LogLevel;

class MigrateDebugConfig extends SystemUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2024070201;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped(): bool {
		$current_value = _elgg_services()->configTable->get('debug');
		$supported_levels = [
			LogLevel::DEBUG,
			LogLevel::INFO,
			LogLevel::NOTICE,
			LogLevel::WARNING,
			LogLevel::ERROR,
			LogLevel::CRITICAL,
			LogLevel::ALERT,
			LogLevel::EMERGENCY
		];
		
		return empty($current_value) || in_array($current_value, $supported_levels);
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
	public function countItems(): int {
		return 1;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {
		$current_value = _elgg_services()->configTable->get('debug');
		
		$new_value = LogLevel::CRITICAL;
		switch($current_value) {
			case 'INFO':
				$new_value = LogLevel::INFO;
				break;
			case 'NOTICE':
				$new_value = LogLevel::NOTICE;
				break;
			case 'WARNING':
				$new_value = LogLevel::WARNING;
				break;
			case 'ERROR':
				$new_value = LogLevel::ERROR;
				break;
		}
		
		if (_elgg_services()->configTable->set('debug', $new_value)) {
			$result->addSuccesses();
		} else {
			$result->addFailures();
		}
		
		return $result;
	}
}
