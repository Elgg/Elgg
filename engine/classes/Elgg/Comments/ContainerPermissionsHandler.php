<?php

namespace Elgg\Comments;

/**
 * Allow commenting on any container
 *
 * @since 4.0
 */
class ContainerPermissionsHandler {
	
	/**
	 * Allow users to comment on entities not owned by them.
	 *
	 * Object being commented on is used as the container of the comment so
	 * permission check must be overridden if user isn't the owner of the object.
	 *
	 * @param \Elgg\Event $event 'container_permissions_check', 'object'
	 *
	 * @return void|true
	 */
	public function __invoke(\Elgg\Event $event) {
		
		// is someone trying to comment, if so override permissions check
		if ($event->getParam('subtype') === 'comment' && $event->getUserParam() instanceof \ElggUser) {
			return true;
		}
	}
}
