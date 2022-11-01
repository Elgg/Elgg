<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Add report user link to hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->isEnabled()) {
			return;
		}
		
		if (elgg_get_logged_in_user_guid() == $user->guid) {
			return;
		}
				
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'reportuser',
			'text' => elgg_echo('reportedcontent:user'),
			'icon' => 'exclamation-triangle',
			'href' => elgg_http_add_url_query_elements('ajax/form/reportedcontent/add', [
				'address' => $user->getURL(),
				'title' => $user->getDisplayName(),
			]),
			'section' => 'action',
			'link_class' => 'elgg-lightbox',
			'deps' => 'elgg/reportedcontent',
		]);
	
		return $return;
	}
}
