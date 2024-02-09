<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;

class RemoveIcontime extends SystemUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2024020901;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
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
		return elgg_get_metadata($this->getOptions([
			'count' => true,
		]));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {
		/* @var $metadata \ElggBatch */
		$metadata = elgg_get_metadata($this->getOptions([
			'offset' => $offset,
		]));
		
		/* @var $md \ElggMetadata */
		foreach ($metadata as $md) {
			if (!$md->delete()) {
				$metadata->reportFailure();
				$result->addFailures();
				continue;
			}
			
			$result->addSuccesses();
		}
		
		return $result;
	}
	
	/**
	 * Get options to metadata queries
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_metadata()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'metadata_names' => 'icontime',
			'limit' => 50,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
		];
		
		return array_merge($defaults, $options);
	}
}
