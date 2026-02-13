<?php

namespace Elgg\Likes;

/**
 * Ajax response handler
 */
class AjaxResponseHandler {

	/**
	 * Alter ajax response to send back likes count
	 *
	 * @param \Elgg\Event $event 'ajax_results', 'all'
	 *
	 * @return \stdClass|null
	 */
	public function __invoke(\Elgg\Event $event): ?\stdClass {
		$entity = get_entity((int) get_input('guid'));
		if (!$entity || elgg_get_viewtype() !== 'default') {
			return null;
		}
		
		/* @var $results \stdClass */
		$results = $event->getValue();

		$results->likes_status = [
			'guid' => $entity->guid,
			'count' => likes_count($entity),
			'count_menu_item' => elgg_view('navigation/menu/elements/item/url', ['item' => _likes_count_menu_item($entity)]),
			'like_menu_item' => elgg_view('navigation/menu/elements/item/url', ['item' => _likes_menu_item($entity)]),
			'is_liked' => DataService::instance()->currentUserLikesEntity($entity->guid),
		];
		
		return $results;
	}
}
