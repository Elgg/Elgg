<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu item to the widget menu
 *
 * @since 4.0
 * @internal
 */
class Widget {

	/**
	 * Register the edit menu item for widgets
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:widget'
	 *
	 * @return void|MenuItems
	 */
	public static function registerEdit(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!(bool) $hook->getParam('show_edit', $widget->canEdit())) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings',
			'text' => elgg_view_icon('settings-alt'),
			'title' => elgg_echo('widget:edit'),
			'href' => "#widget-edit-{$widget->guid}",
			'link_class' => "elgg-widget-edit-button",
			'rel' => 'toggle',
			'priority' => 800,
		]);
		
		return $return;
	}
	
	/**
	 * Register the delete menu item for widgets
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:widget'
	 *
	 * @return void|MenuItems
	 */
	public static function registerDelete(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget || !$widget->canDelete()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_view_icon('delete-alt'),
			'title' => elgg_echo('widget:delete', [$widget->getDisplayName()]),
			'href' => elgg_generate_action_url('widgets/delete', [
				'widget_guid' => $widget->guid,
			]),
			'link_class' => 'elgg-widget-delete-button',
			'id' => "elgg-widget-delete-button-{$widget->guid}",
			'data-elgg-widget-type' => $widget->handler,
			'priority' => 900,
		]);
		
		return $return;
	}
}
