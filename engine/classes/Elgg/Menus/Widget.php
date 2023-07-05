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
	 * @param \Elgg\Event $event 'register', 'menu:widget'
	 *
	 * @return void|MenuItems
	 */
	public static function registerEdit(\Elgg\Event $event) {
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!(bool) $event->getParam('show_edit', $widget->canEdit())) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings',
			'text' => elgg_view_icon('settings-alt'),
			'title' => elgg_echo('widget:edit'),
			'href' => elgg_http_add_url_query_elements('ajax/view/object/widget/edit', [
				'guid' => $widget->guid,
				'show_access' => $event->getParam('show_access', true),
			]),
			'data-colorbox-opts' => json_encode([
				'width' => 750,
				'max-height' => '80%',
				'trapFocus' => false,
				'fixed' => true,
			]),
			'link_class' => ['elgg-widget-edit-button', 'elgg-lightbox'],
			'priority' => 800,
		]);
		
		return $return;
	}
	
	/**
	 * Register the delete menu item for widgets
	 *
	 * @param \Elgg\Event $event 'register', 'menu:widget'
	 *
	 * @return void|MenuItems
	 */
	public static function registerDelete(\Elgg\Event $event) {
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || !$widget->canDelete()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_view_icon('delete-alt'),
			'title' => elgg_echo('widget:delete', [$widget->getDisplayName()]),
			'href' => elgg_generate_action_url('widgets/delete', [
				'widget_guid' => $widget->guid,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
			'link_class' => 'elgg-widget-delete-button',
			'id' => "elgg-widget-delete-button-{$widget->guid}",
			'data-elgg-widget-type' => $widget->handler,
			'priority' => 900,
		]);
		
		return $return;
	}
}
