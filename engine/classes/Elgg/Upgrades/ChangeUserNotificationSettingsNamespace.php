<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\SystemUpgrade;
use Elgg\Upgrade\Result;
use Elgg\Database\Select;
use Elgg\Database\Update;

/**
 * Change the metadata name of the user notification settings to be multi-purpose
 *
 * @since 4.0
 */
class ChangeUserNotificationSettingsNamespace implements SystemUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2021040701;
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
		$select = Select::fromTable('metadata', 'md');
		$entities_table = $select->joinEntitiesTable('md', 'entity_guid');
		
		$select->select('count(*) AS total')
			->where($select->compare('md.name', 'like', 'notification:method:%', ELGG_VALUE_STRING))
			->andWhere($select->compare("{$entities_table}.type", '=', 'user', ELGG_VALUE_STRING));
		
		$result = elgg()->db->getDataRow($select);
		
		return (int) $result->total;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$update = Update::table('metadata');
		$update->set('name', 'REPLACE(name, "notification:method:", "notification:default:")')
			->where($update->compare('name', 'like', 'notification:method:%', ELGG_VALUE_STRING));
		
		$users = $update->subquery('entities');
		$users->select('guid')
			->where($update->compare('type', '=', 'user', ELGG_VALUE_STRING));
		
		$update->andWhere($update->compare('entity_guid', 'in', $users->getSQL()));
		
		$num_rows = elgg()->db->updateData($update, true);
		
		$result->addSuccesses($num_rows);
		
		return $result;
	}
}
