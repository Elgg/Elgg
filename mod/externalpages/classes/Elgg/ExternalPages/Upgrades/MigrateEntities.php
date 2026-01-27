<?php

namespace Elgg\ExternalPages\Upgrades;

use Elgg\Database\EntityTable;
use Elgg\Database\Update;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate individual external pages subtype to a generic type
 */
class MigrateEntities extends AsynchronousUpgrade {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2026012301;
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
		return empty($this->countItems());
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
		$batch = elgg_get_entities($this->getOptions(['offset' => $offset]));

		/* @var $entity \ElggEntity */
		foreach ($batch as $entity) {
			$entity->title = $entity->getSubtype();
			
			$update = Update::table(EntityTable::TABLE_NAME);
			$update->set('subtype', $update->param(\ElggExternalPage::SUBTYPE, ELGG_VALUE_STRING))
				->where($update->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));

			$update->execute(false);
			
			$result->addSuccesses();
		}

		return $result;
	}

	/**
	 * Options for elgg_get_entities()
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_entities()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'type' => 'object',
			'subtypes' => ['about', 'terms', 'privacy'],
			'limit' => 100,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
		];

		return array_merge($defaults, $options);
	}
}
