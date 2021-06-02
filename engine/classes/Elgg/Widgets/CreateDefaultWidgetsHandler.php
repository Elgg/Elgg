<?php

namespace Elgg\Widgets;

/**
 * Creates default widgets
 *
 * @since 4.0
 */
class CreateDefaultWidgetsHandler {
	
	/**
	 * This plugin hook handler is registered for events based on what kinds of
	 * default widgets have been registered. See elgg_default_widgets_init() for
	 * information on registering new default widget contexts.
	 *
	 * @param \Elgg\Event $event <event>, <entity_type>
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		static $processed_events = [];
		
		if (isset($processed_events["{$event->getName()}.{$event->getType()}"])) {
			return;
		}
		// only create default widgets once per event
		$processed_events["{$event->getName()}.{$event->getType()}"] = true;
		
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$default_widget_info = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, []);
		if (empty($default_widget_info)) {
			return;
		}
	
		$type = $entity->getType();
		$subtype = $entity->getSubtype();
	
		foreach ($default_widget_info as $info) {
			if (elgg_extract('event_name', $info) !== $event->getName()) {
				continue;
			}
			
			if (elgg_extract('event_type', $info) !== $event->getType()) {
				continue;
			}
			
			if (elgg_extract('entity_type', $info) !== $type) {
				continue;
			}
	
			$entity_subtype = elgg_extract('entity_subtype', $info, ELGG_ENTITIES_ANY_VALUE);
			if ($entity_subtype !== ELGG_ENTITIES_ANY_VALUE && $entity_subtype !== $subtype) {
				continue;
			}
			
			$widget_context = elgg_extract('widget_context', $info);
			if (empty($widget_context)) {
				continue;
			}
	
			// need to be able to access everything
			elgg_push_context('create_default_widgets');
	
			elgg_call(ELGG_IGNORE_ACCESS, function () use ($entity, $widget_context) {
				// check if there are already widgets
				if (elgg_count_entities([
					'type' => 'object',
					'subtype' => 'widget',
					'owner_guid' => $entity->guid,
					'private_setting_name' => 'context',
					'private_setting_value' => $widget_context,
				])) {
					return;
				}
				
				// pull in by widget context with widget owners as the site
				// not using elgg_get_widgets() because it sorts by columns and we don't care right now.
				$widgets = elgg_get_entities([
					'type' => 'object',
					'subtype' => 'widget',
					'owner_guid' => elgg_get_site_entity()->guid,
					'private_setting_name' => 'context',
					'private_setting_value' => $widget_context,
					'limit' => false,
					'batch' => true,
				]);
				
				/* @var \ElggWidget[] $widgets */
				foreach ($widgets as $widget) {
					// change the container and owner
					$new_widget = clone $widget;
					$new_widget->container_guid = $entity->guid;
					$new_widget->owner_guid = $entity->guid;
		
					// pull in settings
					$settings = $widget->getAllPrivateSettings();
		
					foreach ($settings as $name => $value) {
						$new_widget->$name = $value;
					}
		
					$new_widget->save();
				}
			});
			
			elgg_pop_context();
		}
	}
}
