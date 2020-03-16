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
			// check if the user already has widgets
			if (elgg_get_widgets($user->guid, 'admin')) {
				return;
			}
		
			// In the form column => array of handlers in order, top to bottom
			$adminWidgets = [
				1 => ['control_panel', 'admin_welcome'],
				2 => ['online_users', 'new_users', 'content_stats'],
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
