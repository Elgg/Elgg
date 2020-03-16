<?php

namespace Elgg\Upgrade;

/**
 * Admin notices for new upgrades
 *
 * @since 4.0
 */
class CreateAdminNoticeHandler {
	
	/**
	 * Add an admin notice when a new \ElggUpgrade object is created.
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		if (!$event->getObject() instanceof \ElggUpgrade) {
			return;
		}
		
		// Link to the Upgrades section
		$link = elgg_view('output/url', [
			'href' => 'admin/upgrades',
			'text' => elgg_echo('admin:view_upgrades'),
		]);
	
		$message = elgg_echo('admin:pending_upgrades');
	
		elgg_add_admin_notice('pending_upgrades', "$message $link");
	}
}
