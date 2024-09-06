<?php

namespace Elgg\Likes;

/**
 * Likes preloader
 */
class Preloader {

	/**
	 * @var \Elgg\Likes\DataService
	 */
	protected $data;

	/**
	 * Create a preloader
	 *
	 * @param \Elgg\Likes\DataService $data a dataservice
	 */
	public function __construct(DataService $data) {
		$this->data = $data;
	}

	/**
	 * Preload likes for a set of items
	 *
	 * @param \ElggRiverItem[]|\ElggEntity[] $items the items to preload for
	 *
	 * @return void
	 */
	public function preloadForList(array $items) {
		$guids = $this->getGuidsToPreload($items);
		if (count($guids) < 2) {
			return;
		}

		$this->preloadCurrentUserLikes($guids);

		$guids_remaining = $this->preloadCountsFromEvent($this->getEntities($guids));
		if (!empty($guids_remaining)) {
			$this->preloadCountsFromQuery($guids_remaining);
		}
	}

	/**
	 * Preload likes count based on guids
	 *
	 * @param int[] $guids the guids to preload
	 *
	 * @return void
	 */
	protected function preloadCountsFromQuery(array $guids) {
		$count_rows = elgg_get_annotations([
			'annotation_names' => 'likes',
			'guids' => $guids,
			'selects' => ['e.guid', 'COUNT(*) AS cnt'],
			'group_by' => 'e.guid',
			'limit' => false,
			'callback' => false,
		]);
		foreach ($guids as $guid) {
			$this->data->setNumLikes($guid, 0);
		}
		
		foreach ($count_rows as $row) {
			$this->data->setNumLikes($row->guid, $row->cnt);
		}
	}

	/**
	 * Preload based of entities
	 *
	 * @param \ElggEntity[] $entities given entities
	 *
	 * @return int[]
	 */
	protected function preloadCountsFromEvent(array $entities) {
		$guids_not_loaded = [];

		foreach ($entities as $entity) {
			// BC with likes_count(). If this event is used this preloader may not be of much help.
			$type = $entity->getType();
			$params = ['entity' => $entity];

			$num_likes = elgg_trigger_event_results('likes:count', $type, $params, false);
			if ($num_likes) {
				$this->data->setNumLikes($entity->guid, $num_likes);
			} else {
				$guids_not_loaded[] = $entity->guid;
			}
		}

		return $guids_not_loaded;
	}

	/**
	 * Preload likes for given guids for current user
	 *
	 * @param int[] $guids preload guids
	 *
	 * @return void
	 */
	protected function preloadCurrentUserLikes(array $guids) {
		$owner_guid = elgg_get_logged_in_user_guid();
		if (!$owner_guid) {
			return;
		}

		$annotation_rows = elgg_get_annotations([
			'annotation_names' => 'likes',
			'annotation_owner_guids' => $owner_guid,
			'guids' => $guids,
			'limit' => false,
			'callback' => false,
		]);

		foreach ($guids as $guid) {
			$this->data->setLikedByCurrentUser($guid, false);
		}
		
		foreach ($annotation_rows as $row) {
			$this->data->setLikedByCurrentUser($row->entity_guid, true);
		}
	}

	/**
	 * Convert river items and/or entities to guids
	 *
	 * @param \ElggRiverItem[]|\ElggEntity[] $items the items to process
	 *
	 * @return int[]
	 */
	protected function getGuidsToPreload(array $items) {
		$guids = [];

		foreach ($items as $item) {
			if ($item instanceof \ElggRiverItem) {
				$object = $item->getObjectEntity();
				if (!$object instanceof \ElggEntity) {
					continue;
				}

				// only like group creation #3958
				if ($object instanceof \ElggGroup && $item->view != 'river/group/create') {
					continue;
				}

				if (!$object->hasCapability('likable')) {
					continue;
				}

				if ($item->annotation_id != 0) {
					continue;
				}

				if ($item->object_guid) {
					$guids[$item->object_guid] = true;
				}
			} elseif ($item instanceof \ElggEntity) {
				if ($item->hasCapability('likable')) {
					$guids[$item->guid] = true;
				}
			}
		}
		
		return array_keys($guids);
	}

	/**
	 * Get entities in any order checking cache first
	 *
	 * @param int[] $guids guids of entities to return
	 *
	 * @return \ElggEntity[]
	 */
	protected function getEntities(array $guids) {
		// most objects are already preloaded
		$entities = [];
		$fetch_guids = [];

		foreach ($guids as $guid) {
			$entity = _elgg_services()->entityCache->load($guid);
			if ($entity) {
				$entities[] = $entity;
			} else {
				$fetch_guids[] = $guid;
			}
		}
		
		if ($fetch_guids) {
			$fetched = elgg_get_entities([
				'guids' => $fetch_guids,
				'limit' => false,
			]);
			array_splice($entities, count($entities), 0, $fetched);
		}
		
		return $entities;
	}
	
	/**
	 * Event handler for listings to determine if preloading is needed
	 *
	 * @param \Elgg\Event $event 'view_vars', 'page/components/list'
	 *
	 * @return void
	 */
	public static function preload(\Elgg\Event $event) {
		$vars = $event->getValue();
		
		$items = (array) elgg_extract('items', $vars, []);
		if (!elgg_is_logged_in() || count($items) < 3) {
			return;
		}
		
		$preload = elgg_extract('preload_likes', $vars);
		if (!isset($preload) && !elgg_in_context('widgets')) {
			$list_classes = elgg_extract_class($vars, [], 'list_class');
			$preload_list_classes = ['comments-list', 'elgg-list-entity', 'elgg-list-river', 'elgg-river-comments'];
			$intersect = array_intersect($list_classes, $preload_list_classes);
			
			$preload = count($intersect) > 0;
		}
		
		if (empty($preload)) {
			return;
		}
		
		$preloader = new self(\Elgg\Likes\DataService::instance());
		$preloader->preloadForList($items);
	}
}
