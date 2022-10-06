<?php

namespace Elgg\Likes;

/**
 * Ajax response handler
 */
class AjaxResponseHandler {

	/**
	 * Alter ajax response to send back likes count
	 *
	 * @param \Elgg\Event $event \Elgg\Services\AjaxResponse::RESPONSE_EVENT, 'all'
	 *
	 * @return void|\Elgg\Services\AjaxResponse
	 */
	public function __invoke(\Elgg\Event $event) {
		$entity = get_entity((int) get_input('guid'));
		if (!$entity || elgg_get_viewtype() !== 'default') {
			return;
		}
		
		/* @var $response \Elgg\Services\AjaxResponse */
		$response = $event->getValue();
		
		$response->getData()->likes_status = [
			'guid' => $entity->guid,
			'count' => likes_count($entity),
			'count_menu_item' => elgg_view('navigation/menu/elements/item/url', ['item' => _likes_count_menu_item($entity)]),
			'like_menu_item' => elgg_view('navigation/menu/elements/item/url', ['item' => _likes_menu_item($entity)]),
			'is_liked' => DataService::instance()->currentUserLikesEntity($entity->guid),
		];
		
		return $response;
	}
}
