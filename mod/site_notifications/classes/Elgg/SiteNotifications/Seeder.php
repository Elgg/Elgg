<?php

namespace Elgg\SiteNotifications;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

/**
 * Add site notification seed
 *
 * @internal
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());
		
		while ($this->getCount() < $this->limit) {
			try {
				/* @var $notification \SiteNotification */
				$notification = $this->createObject([
					'subtype' => 'site_notification',
					'access_id' => ACCESS_PRIVATE,
					'read' => $this->faker()->boolean(),
					'summary' => $this->faker()->sentence(),
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create site notification with the given options
				continue;
			}
			
			// link to entity on site
			$linked_entity = $this->getRandomLinkedEntity([
				$notification->guid, // prevent deadloops
			]);
			if ($linked_entity instanceof \ElggEntity) {
				$notification->setLinkedEntity($linked_entity);
			}
			
			// set notification actor
			$actor = $this->getRandomUser([$notification->owner_guid]);
			if ($actor instanceof \ElggUser) {
				$notification->setActor($actor);
			}
			
			$notification->save();
			
			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		/* @var $notifications \ElggBatch */
		$notifications = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'site_notification',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		
		/* @var $notification \SiteNotification */
		foreach ($notifications as $notification) {
			if ($notification->delete()) {
				$this->log("Deleted site notification {$notification->guid}");
			} else {
				$this->log("Failed to delete site notification {$notification->guid}");
				$notifications->reportFailure();
				continue;
			}
			
			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'site_notification';
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'site_notification',
		];
	}
	
	/**
	 * Get a rondom seeded entity to link the notification to
	 *
	 * @param array $exclude      GUIDs to exclude in the search
	 * @param bool  $allow_create allow creation of new entities
	 *
	 * @return \ElggEntity|false
	 */
	protected function getRandomLinkedEntity(array $exclude = [], bool $allow_create = true) {
		$exclude[] = 0;
		
		$entities = elgg_get_entities([
			'types' => ['object', 'group'],
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($exclude) {
					return $qb->compare("{$main_alias}.guid", 'NOT IN', $exclude, ELGG_VALUE_INTEGER);
				},
				function (QueryBuilder $qb, $main_alias) {
					// prevent linking to other site notifications
					return $qb->compare("{$main_alias}.subtype", '!=', 'site_notification', ELGG_VALUE_STRING);
				},
			],
			'order_by' => new OrderByClause('RAND()', null),
		]);
		
		if (!empty($entities)) {
			return $entities[0];
		}
		
		if ($allow_create) {
			return $this->createObject();
		}
		
		return false;
	}
}
