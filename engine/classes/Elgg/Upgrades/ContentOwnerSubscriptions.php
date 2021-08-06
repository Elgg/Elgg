<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;

/**
 * Subscribe all content owners to their own content
 *
 * @since 4.0
 */
class ContentOwnerSubscriptions implements SystemUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2021060401;
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
		$methods = elgg_get_notification_methods();
		if (empty($methods)) {
			return true;
		}
		
		return empty($this->countItems());
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
		
		/* @var $entities \ElggBatch */
		$entities = elgg_get_entities($this->getOptions([
			'offset' => $offset,
		]));
		
		// get registered notification methods
		$methods = elgg_get_notification_methods();
		
		$process_entity = function (\ElggEntity $entity) use (&$result) {
			// using setMetadata because we don't want te rely on the magic setters,
			// as they can store data in a different table (eg. widgets, plugins)
			$entity->setMetadata('__content_owner_subscription_upgrade_migrated', time());
			
			$result->addSuccesses();
		};
		
		/* @var $entity \ElggEntity */
		foreach ($entities as $entity) {
			$owner = $entity->getOwnerEntity();
			if (!$owner instanceof \ElggUser) {
				// how did this happen?
				$process_entity($entity);
				continue;
			}
			
			if ($entity->hasMutedNotifications($owner->guid)) {
				// user already blocked notifications
				$process_entity($entity);
				continue;
			}
			
			if ($entity->hasSubscriptions($owner->guid)) {
				// already subscribed
				$process_entity($entity);
				continue;
			}
			
			// get user preferences
			$content_preferences = $owner->getNotificationSettings('content_create');
			$enabled_methods = array_keys(array_filter($content_preferences));
			if (empty($enabled_methods)) {
				$process_entity($entity);
				continue;
			}
			
			// loop through all notification types
			foreach ($enabled_methods as $method) {
				// only enable supported methods
				if (!in_array($method, $methods)) {
					continue;
				}
				
				$entity->addSubscription($owner->guid, $method);
			}
			
			$process_entity($entity);
		}
		
		return $result;
	}
	
	/**
	 * Get query options
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 */
	protected function getOptions(array $options = []): array {
		$upgrade = $this->getUpgradeEntity();
		
		$defaults = [
			'created_before' => $upgrade->time_created,
			'limit' => 100,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$owner_guids = $qb->subquery('entities');
					$owner_guids->select('guid')
						->andWhere($qb->compare('type', '=', 'user', ELGG_VALUE_STRING));
					
					return $qb->compare("{$main_alias}.owner_guid", 'in', $owner_guids->getSQL());
				},
				function (QueryBuilder $qb, $main_alias) {
					$metadata = $qb->subquery('metadata', 'md');
					$metadata->select('md.entity_guid')
						->where($qb->compare('md.name', '=', '__content_owner_subscription_upgrade_migrated', ELGG_VALUE_STRING));
					
					return $qb->compare("{$main_alias}.guid", 'NOT IN', $metadata->getSQL());
				},
				function (QueryBuilder $qb, $main_alias) {
					// exclude some subtypes of objects
					$object = $qb->merge([
						$qb->compare("{$main_alias}.type", '=', 'object', ELGG_VALUE_STRING),
						$qb->compare("{$main_alias}.subtype", 'NOT IN', ['widget', 'site_notification', 'messages'], ELGG_VALUE_STRING),
					], 'AND');
					
					// migrate objects and groups
					return $qb->merge([
						$qb->compare("{$main_alias}.type", '=', 'group', ELGG_VALUE_STRING),
						$object,
					], 'OR');
				},
			],
		];
		
		return array_merge($defaults, $options);
	}
	
	/**
	 * Get the ElggUpgrade for this Upgrade Batch
	 *
	 * @return \ElggUpgrade
	 */
	protected function getUpgradeEntity(): \ElggUpgrade {
		return _elgg_services()->upgradeLocator->getUpgradeByClass(self::class);
	}
}
