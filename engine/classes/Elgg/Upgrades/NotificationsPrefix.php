<?php

namespace Elgg\Upgrades;

use Elgg\Database\Delete;
use Elgg\Database\QueryBuilder;
use Elgg\Database\RelationshipsTable;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Notifications\SubscriptionsService;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate the notification subscription relationship to a new naming convention
 *
 * @since 4.0
 */
class NotificationsPrefix extends AsynchronousUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2021022401;
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
	public function shouldBeSkipped(): bool {
		$methods = _elgg_services()->notifications->getMethods();
		
		return empty($methods) || empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return elgg_count_entities($this->getEntityGUIDOptions());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$relationship_prefix = SubscriptionsService::RELATIONSHIP_PREFIX;
		$methods = _elgg_services()->notifications->getMethods();
		
		$guids = elgg_get_entities($this->getEntityGUIDOptions([
			'offset' => $offset,
		]));
		
		foreach ($methods as $method) {
			$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'r1');
			
			// exclude already migrated relationships
			$exists = $select->subquery(RelationshipsTable::TABLE_NAME, 'r2');
			$exists->select('1')
				->where($select->compare("{$select->getTableAlias()}.guid_one", '=', "{$exists->getTableAlias()}.guid_one"))
				->andWhere($select->compare("{$select->getTableAlias()}.guid_two", '=', "{$exists->getTableAlias()}.guid_two"))
				->andWhere($select->compare("{$exists->getTableAlias()}.relationship", '=', "{$relationship_prefix}:{$method}", ELGG_VALUE_STRING));
			
			// get old relationships
			$select->select('id')
				->where($select->compare("{$select->getTableAlias()}.relationship", '=', "{$relationship_prefix}{$method}", ELGG_VALUE_STRING))
				->andWhere($select->compare("{$select->getTableAlias()}.guid_one", 'in', $guids, ELGG_VALUE_GUID))
				->andWhere("NOT EXISTS ({$exists->getSQL()})");
			
			$ids = _elgg_services()->db->getData($select, function($row) {
				return (int) $row->id;
			});
			if (!empty($ids)) {
				// update old relationships to new relationship
				$update = Update::table(RelationshipsTable::TABLE_NAME);
				$update->set('relationship', $update->param("{$relationship_prefix}:{$method}", ELGG_VALUE_STRING))
					->where($update->compare('relationship', '=', "{$relationship_prefix}{$method}", ELGG_VALUE_STRING))
					->andWhere($update->compare('id', 'in', $ids, ELGG_VALUE_ID));
				
				_elgg_services()->db->updateData($update);
			}
			
			// delete old relationships that couldn't be migrated because of key constraints
			$delete = Delete::fromTable(RelationshipsTable::TABLE_NAME);
			$delete->where($delete->compare('guid_one', 'in', $guids, ELGG_VALUE_GUID))
				->andWhere($delete->compare('relationship', '=', "{$relationship_prefix}{$method}", ELGG_VALUE_STRING));
			
			_elgg_services()->db->deleteData($delete);
		}
		
		$result->addSuccesses(count($guids));
		
		return $result;
	}
	
	/**
	 * Get options for entity guid selection
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 */
	protected function getEntityGUIDOptions(array $options = []): array {
		$methods = _elgg_services()->notifications->getMethods();
		
		$defaults = [
			'limit' => 100,
			'callback' => function($row) {
				return (int) $row->guid;
			},
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($methods) {
					$rel = $qb->joinRelationshipTable($main_alias, 'guid', null, true);
					
					$old_relationships = [];
					foreach ($methods as $method) {
						$old_relationships[] = SubscriptionsService::RELATIONSHIP_PREFIX . $method;
					}
					
					return $qb->compare("{$rel}.relationship", 'in', $old_relationships, ELGG_VALUE_STRING);
				},
			],
		];
		
		return array_merge($defaults, $options);
	}
}
