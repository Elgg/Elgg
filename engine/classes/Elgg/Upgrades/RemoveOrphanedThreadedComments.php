<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

class RemoveOrphanedThreadedComments implements AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2023011701;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return elgg_count_entities($this->getOptions());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		/* @var $batch \ElggBatch */
		$batch = elgg_get_entities($this->getOptions([
			'offset' => $offset,
		]));
		/* @var $comment \ElggComment */
		foreach ($batch as $comment) {
			if ($comment->delete()) {
				$result->addSuccesses();
				continue;
			}
			
			$result->addError();
		}
		
		return $result;
	}
	
	/**
	 * Get the options to fetch orphaned comments
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_entities()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'type' => 'object',
			'subtype' => 'comment',
			'limit' => 100,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
			'batch_size' => 50,
			'metadata_name_value_pairs' => [
				'name' => 'parent_guid',
				'value' => 0,
				'operand' => '>'
			],
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$sub = $qb->subquery('entities');
					$sub->select('guid')
						->where($qb->compare('type', '=', 'object', ELGG_VALUE_STRING))
						->andWhere($qb->compare('subtype', '=', 'comment', ELGG_VALUE_STRING));
					
					$md = $qb->joinMetadataTable($main_alias, 'guid', 'parent_guid');
					return $qb->compare("{$md}.value", 'NOT IN', $sub->getSQL());
				}
			],
		];
		
		return array_merge($defaults, $options);
	}
}
