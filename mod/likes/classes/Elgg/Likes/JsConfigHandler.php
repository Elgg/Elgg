<?php

namespace Elgg\Likes;

/**
 * Javascript confif handler
 */
class JsConfigHandler {

	/**
	 * Send config data to the likes module
	 *
	 * @param \Elgg\Event $event Hook info
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$value = $event->getValue();

		$value['likes_states'] = [
			'unliked' => [
				'html' => elgg_view_icon('thumbs-up'),
				'title' => elgg_echo('likes:likethis'),
				'action' => 'likes/add',
				'next_state' => 'liked',
			],
			'liked' => [
				'html' => elgg_view_icon('thumbs-up', ['class' => 'elgg-state-active']),
				'title' => elgg_echo('likes:remove'),
				'action' => 'likes/delete',
				'next_state' => 'unliked',
			],
		];

		return $value;
	}
}
