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
		$default_widget_info = _elgg_config()->default_widget_info;
		$entity = $event->getObject();
		
		if (empty($default_widget_info) || !$entity instanceof \ElggEntity) {
			return;
		}
	
		$type = $entity->getType();
		$subtype = $entity->getSubtype();
	
		// event is already guaranteed by the hook registration.
		// need to check subtype and type.
		foreach ($default_widget_info as $info) {
			if (elgg_extract('entity_type', $info) !== $type) {
				continue;
			}
	
			$entity_subtype = elgg_extract('entity_subtype', $info);
			if ($entity_subtype !== ELGG_ENTITIES_ANY_VALUE && $entity_subtype !== $subtype) {
				continue;
			}
	
			// need to be able to access everything
			elgg_push_context('create_default_widgets');
	
			elgg_call(ELGG_IGNORE_ACCESS, function () use ($entity, $info) {
				// pull in by widget context with widget owners as the site
				// not using elgg_get_widgets() because it sorts by columns and we don't care right now.
				$widgets = elgg_get_entities([
					'type' => 'object',
					'subtype' => 'widget',
					'owner_guid' => elgg_get_site_entity()->guid,
					'private_setting_name' => 'context',
					'private_setting_value' => elgg_extract('widget_context', $info),
					'limit' => 0,
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
