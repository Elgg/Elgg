<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Result;

class MigrateEntityIconCroppingCoordinates extends \Elgg\Upgrade\SystemUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2024020101;
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
		return elgg_count_entities($this->getOptions());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {
		/* @var $entities \ElggBatch */
		$entities = elgg_get_entities($this->getOptions([
			'offset' => $offset,
		]));
		/* @var $entity \ElggEntity */
		foreach ($entities as $entity) {
			$coords = [
				'x1' => (int) $entity->x1,
				'x2' => (int) $entity->x2,
				'y1' => (int) $entity->y1,
				'y2' => (int) $entity->y2,
			];
			
			try {
				$entity->saveIconCoordinates($coords, 'icon');
			} catch (\Elgg\Exceptions\ExceptionInterface $e) {
				// something went wrong with the coords, probably broken
			}
			
			unset($entity->x1);
			unset($entity->x2);
			unset($entity->y1);
			unset($entity->y2);
			
			$result->addSuccesses();
		}
		
		return $result;
	}
	
	/**
	 * Get options for the upgrade
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_entities()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'limit' => 50,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
			'metadata_names' => [
				'x1',
				'x2',
				'y1',
				'y2',
			],
		];
		
		return array_merge($defaults, $options);
	}
}
