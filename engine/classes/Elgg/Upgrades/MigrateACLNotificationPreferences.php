<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use Elgg\Database\QueryBuilder;

/**
 * Migrate the old access collection notification preferences to the new logic
 * The old settings are from the Notifications plugin
 *
 * @since 4.0
 */
class MigrateACLNotificationPreferences implements AsynchronousUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2021040801;
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
		return empty($this->countItems());
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return elgg_get_metadata([
			'type' => 'user',
			'count' => true,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.name", 'like', 'collections_notifications_preferences_%', ELGG_VALUE_STRING);
				},
			],
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$metadata = elgg_get_metadata([
			'type' => 'user',
			'limit' => 50,
			'offset' => $offset,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.name", 'like', 'collections_notifications_preferences_%', ELGG_VALUE_STRING);
				},
			],
		]);
		
		$remove_md = function (\ElggMetadata $md) use (&$result) {
			if ($md->delete()) {
				$result->addSuccesses();
			} else {
				$result->addFailures();
			}
		};
		
		/* @var $md \ElggMetadata */
		foreach ($metadata as $md) {
			if ($md->value !== -1) {
				// preference for an access collection which isn't migrated
				$remove_md($md);
				continue;
			}
			
			$method = substr($md->name, strlen('collections_notifications_preferences_'));
			$user = $md->getEntity();
			if ($user instanceof \ElggUser) {
				// only truthy values were saved
				$user->setNotificationSetting($method, true, 'friends');
			}
			
			$remove_md($md);
		}
		
		return $result;
	}
}
