<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Database\Delete;

/**
 * Migrate the notification subscription relationship to a new naming convention
 *
 * @since 4.0
 */
class NotificationsPrefix implements AsynchronousUpgrade {

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
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		$methods = _elgg_services()->notifications->getMethods();
		
		$old_relationships = [];
		foreach ($methods as $method) {
			$old_relationships[] = "notify{$method}";
		}
		
		$select = Select::fromTable('entity_relationships');
		$select->select('count(*) as total')
			->where($select->compare('relationship', 'in', $old_relationships, ELGG_VALUE_STRING));
		
		$result = _elgg_services()->db->getDataRow($select);
		
		return (int) $result->total;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$methods = _elgg_services()->notifications->getMethods();
		
		// migrate from 'notifymethod' to 'notify:method'
		foreach ($methods as $method) {
			$update = Update::table('entity_relationships', 'r1');
			
			// prevent duplicate key contraints during update
			$sub = $update->subquery('entity_relationships', 'r2');
			$sub->select('1')
				->andWhere($update->compare('r1.guid_one', '=', 'r2.guid_one'))
				->andWhere($update->compare('r1.guid_two', '=', 'r2.guid_two'))
				->andWhere($update->compare('r2.relationship', '=', "notify:{$method}", ELGG_VALUE_STRING));
			
			$update->set('r1.relationship', $update->param("notify:{$method}", ELGG_VALUE_STRING))
				->where($update->compare('r1.relationship', '=', "notify{$method}", ELGG_VALUE_STRING))
				->andWhere($update->compare(null, 'not exists', $sub->getSQL()));
			
			$num_rows = _elgg_services()->db->updateData($update, true);
			$result->addSuccesses($num_rows);
			
			// cleanup all not migrated (duplicate key) rows
			$delete = Delete::fromTable('entity_relationships');
			$delete->where($delete->compare('relationship', '=', "notify{$method}", ELGG_VALUE_STRING));
			
			$num_rows = _elgg_services()->db->deleteData($delete);
			$result->addSuccesses($num_rows);
		}
		
		return $result;
	}
}
