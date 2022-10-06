<?php

namespace Elgg\Comments;

/**
 * Check commentable capability for a container
 *
 * @since 4.1
 */
class ContainerLogicHandler {
	
	/**
	 * Prevent commenting on a container if container is not commentable
	 *
	 * @param \Elgg\Event $event 'container_logic_check', 'all'
	 *
	 * @return void|false
	 */
	public function __invoke(\Elgg\Event $event) {
		if ($event->getParam('subtype') !== 'comment') {
			return;
		}
		
		$container = $event->getParam('container');
		if (!$container instanceof \ElggEntity) {
			return;
		}
		
		if (!$container->hasCapability('commentable')) {
			return false;
		}
	}
}
