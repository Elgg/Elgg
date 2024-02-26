<?php

namespace Elgg\Comments;

/**
 * Comments preloader
 *
 * @since 4.1
 * @internal
 */
class Preloader {

	/**
	 * Create a preloader
	 *
	 * @param \Elgg\Comments\DataService $data a dataservice
	 */
	public function __construct(protected DataService $data) {
	}

	/**
	 * Preload comments count for a set of items
	 *
	 * @param \ElggEntity[]|\ElggRiverItem[] $items the items to preload for
	 *
	 * @return void
	 */
	public function preloadForList(array $items): void {
		$guids = $this->getGuidsToPreload($items);
	
		$this->preloadCountsFromQuery($guids);
	}

	/**
	 * Preload comments count based on guids
	 *
	 * @param int[] $guids the guids to preload
	 *
	 * @return void
	 */
	protected function preloadCountsFromQuery(array $guids): void {
		if (empty($guids)) {
			return;
		}
		
		$count_rows = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guids' => $guids,
			'selects' => ['e.container_guid', 'COUNT(*) AS cnt'],
			'group_by' => 'e.container_guid',
			'limit' => false,
			'callback' => false,
		]);
		
		foreach ($guids as $guid) {
			$this->data->setCommentsCount($guid, 0);
		}
		
		foreach ($count_rows as $row) {
			$this->data->setCommentsCount($row->container_guid, $row->cnt);
		}
	}

	/**
	 * Convert entities to guids
	 *
	 * @param \ElggEntity[]|\ElggRiverItem[] $items the entities to process
	 *
	 * @return int[]
	 */
	protected function getGuidsToPreload(array $items): array {
		$guids = [];

		foreach ($items as $item) {
			if ($item instanceof \ElggEntity) {
				if ($item->hasCapability('commentable')) {
					$guids[$item->guid] = true;
				}
			} elseif ($item instanceof \ElggRiverItem) {
				$guids[$item->object_guid] = true;
			}
		}
		
		return $this->data->filterGuids(array_keys($guids));
	}
	
	/**
	 * Event handler for listings to determine if preloading is needed
	 *
	 * @param \Elgg\Event $event 'view_vars', 'page/components/list'
	 *
	 * @return void
	 */
	public static function preload(\Elgg\Event $event): void {
		$vars = $event->getValue();
		
		$items = (array) elgg_extract('items', $vars, []);
		if (!elgg_is_logged_in() || count($items) < 3) {
			return;
		}
		
		$preload = elgg_extract('preload_comments_count', $vars);
		if (!isset($preload)) {
			$list_class = elgg_extract('list_class', $vars);
			$preload = !elgg_in_context('widgets') && in_array($list_class, ['elgg-list-river', 'elgg-list-entity', 'comments-list']);
		}
		
		if (!$preload) {
			return;
		}
		
		$preloader = new self(\Elgg\Comments\DataService::instance());
		$preloader->preloadForList($items);
	}
}
