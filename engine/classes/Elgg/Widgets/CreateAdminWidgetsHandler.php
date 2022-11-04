<?php

namespace Elgg\Widgets;

/**
 * Creates admin widgets
 *
 * @since 4.0
 */
class CreateAdminWidgetsHandler {
	
	/**
	 * Adds default admin widgets to the admin dashboard.
	 *
	 * @param \Elgg\Event $event 'make_admin', 'user'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$user = $event->getObject();
	
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
			if (empty($user->guid)) {
				// do not create widgets for unsaved entities... probably during unit testing
				return;
			}
			
			// check if the user already has widgets
			if (elgg_get_widgets($user->guid, 'admin')) {
				return;
			}
		
			// In the form column => array of handlers in order, top to bottom
			$adminWidgets = [
				1 => ['admin_welcome', 'elgg_blog', 'content_stats'],
				2 => ['online_users', 'new_users'],
			];
		
			foreach ($adminWidgets as $column => $handlers) {
				foreach ($handlers as $position => $handler) {
					$guid = elgg_create_widget($user->guid, $handler, 'admin');
					if ($guid === false) {
						continue;
					}
					
					/* @var \ElggWidget $widget */
					$widget = get_entity($guid);
					$widget->move($column, $position);
				}
			}
		});
	}
}
