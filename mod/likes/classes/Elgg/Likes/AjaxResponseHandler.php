<?php

namespace Elgg\Likes;

use Elgg\Services\AjaxResponse;

/**
 * Ajax response handler
 */
class AjaxResponseHandler {

	/**
	 * Alter ajax response to send back likes count
	 *
	 * @param string                      $hook     Hook name (AjaxResponse::RESPONSE_HOOK)
	 * @param string                      $type     'all'
	 * @param \Elgg\Services\AjaxResponse $response Ajax response
	 * @param array                       $params   Hook params
	 *
	 * @return void|\Elgg\Services\AjaxResponse
	 */
	public function __invoke($hook, $type, AjaxResponse $response, $params) {
		$entity = get_entity(get_input('guid'));
		if (!$entity) {
			return;
		}
		
		$response->getData()->likes_status = [
			'guid' => $entity->guid,
			'count' => likes_count($entity),
			'count_menu_item' => elgg_view_menu_item(_likes_count_menu_item($entity)),
			'like_menu_item' => elgg_view_menu_item(_likes_menu_item($entity)),
			'is_liked' => DataService::instance()->currentUserLikesEntity($entity->guid),
		];
		
		return $response;
	}
}
