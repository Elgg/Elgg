<?php

namespace Elgg\Widgets;

/**
 * Bypasses permissions for default widgets
 *
 * @since 4.0
 */
class DefaultWidgetsContainerPermissionsHandler {
	
	/**
	 * Overrides permissions checks when creating widgets for logged out users.
	 *
	 * @param \Elgg\Event $event 'container_permissions_check', 'object'
	 *
	 * @return void|true
	 */
	public function __invoke(\Elgg\Event $event) {
		if ($event->getParam('subtype') !== 'widget') {
			return;
		}
		
		if (elgg_in_context('create_default_widgets')) {
			return true;
		}
	}
}
