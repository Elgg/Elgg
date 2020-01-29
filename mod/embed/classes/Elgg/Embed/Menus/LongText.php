<?php

namespace Elgg\Embed\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class LongText {

	/**
	 * Add the embed menu item to the long text menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:longtext'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
		
		if (elgg_get_context() == 'embed') {
			return;
		}
		
		$id = $hook->getParam('textarea_id');
		if ($id === null) {
			return;
		}
	
		$url = 'embed';
	
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggGroup && $page_owner->isMember()) {
			$url = elgg_http_add_url_query_elements($url, [
				'container_guid' => $page_owner->guid,
			]);
		}
	
		$items = $hook->getValue();
		$items[] = \ElggMenuItem::factory([
			'name' => 'embed',
			'href' => false,
			'data-colorbox-opts' => json_encode([
				'href' => elgg_normalize_url($url),
			]),
			'text' => elgg_echo('embed:media'),
			'rel' => "embed-lightbox-{$id}",
			'link_class' => "elgg-longtext-control elgg-lightbox embed-control embed-control-{$id} elgg-lightbox",
			'deps' => ['elgg/embed'],
			'priority' => 10,
		]);
	
		return $items;
	}
}
