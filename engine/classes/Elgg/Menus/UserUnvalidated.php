<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the user:unvalidated menu
 *
 * @since 4.0
 * @internal
 */
class UserUnvalidated {
	
	/**
	 * Register default links
	 *
	 * @param \Elgg\Event $event 'register', 'menu:user:unvalidated'
	 *
	 * @return void|MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggUser || $entity->isValidated()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'change_email',
			'icon' => 'edit',
			'text' => elgg_echo('admin:users:unvalidated:change_email'),
			'href' => elgg_http_add_url_query_elements('ajax/form/admin/user/change_email', [
				'user_guid' => $entity->guid,
			]),
			'link_class' => 'elgg-lightbox',
			'priority' => 100,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'validate',
			'icon' => 'check',
			'text' => elgg_echo('validate'),
			'href' => elgg_generate_action_url('admin/user/validate', [
				'user_guid' => $entity->guid,
			]),
			'confirm' => true,
			'priority' => 400,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'title' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('admin/user/delete', [
				'guid' => $entity->guid,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 500,
		]);
		
		return $return;
	}
}
