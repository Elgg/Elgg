<?php

namespace Elgg\Likes;

use Elgg\Services\AjaxResponse;

class AjaxResponseHandler {

	/**
	 * Alter ajax response to send back likes count
	 *
	 * @param string       $hook     Hook name (AjaxResponse::RESPONSE_HOOK)
	 * @param string       $type     'all'
	 * @param AjaxResponse $response Ajax response
	 * @param array        $params   Hook params
	 */
	public function __invoke($hook, $type, AjaxResponse $response, $params) {
		$entity = get_entity(get_input('guid'));
		if ($entity) {
			$response->getData()->likes_status = [
				'guid' => $entity->guid,
				'count' => likes_count($entity),
				'is_liked' => DataService::instance()->currentUserLikesEntity($entity->guid),
			];
		}
	}
}
