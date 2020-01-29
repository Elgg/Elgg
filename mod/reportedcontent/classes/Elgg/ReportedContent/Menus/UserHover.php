<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Add report user link to hover menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		if (elgg_get_logged_in_user_guid() == $user->guid) {
			return;
		}
				
		$return = $hook->getValue();
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
