<?php
/**
 * Elgg widgets library.
 * Contains code for handling widgets.
 *
 * @package Elgg.Core
 * @subpackage Widgets
 */

/**
 * Get widgets for a particular context
 *
 * The widgets are ordered for display and grouped in columns.
 * $widgets = elgg_get_widgets(elgg_get_logged_in_user_guid(), 'dashboard');
 * $first_column_widgets = $widgets[1];
 *
 * @param int    $owner_guid The owner GUID of the layout
 * @param string $context    The context (profile, dashboard, etc)
 *
 * @return array An 2D array of \ElggWidget objects
 * @since 1.8.0
 */
function elgg_get_widgets($owner_guid, $context) {
	return _elgg_services()->widgets->getWidgets($owner_guid, $context);
}

/**
 * Create a new widget instance
 *
 * @param int    $owner_guid GUID of entity that owns this widget
 * @param string $handler    The handler for this widget
 * @param string $context    The context for this widget
 * @param int    $access_id  If not specified, it is set to the default access level
 *
 * @return int|false Widget GUID or false on failure
 * @since 1.8.0
 */
function elgg_create_widget($owner_guid, $handler, $context, $access_id = null) {
	return _elgg_services()->widgets->createWidget($owner_guid, $handler, $context, $access_id);
}

/**
 * Can the user edit the widget layout
 *
 * Triggers a 'permissions_check', 'widget_layout' plugin hook
 *
 * @param string $context   The widget context
 * @param int    $user_guid The GUID of the user (0 for logged in user)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_can_edit_widget_layout($context, $user_guid = 0) {
	return _elgg_services()->widgets->canEditLayout($context, $user_guid);
}

/**
 * Register a widget type
 *
 * This should be called by plugins in their init function.
 *
 * @param string $handler     The identifier for the widget handler
 * @param string $name        The name of the widget type
 * @param string $description A description for the widget type
 * @param array  $context     An array of contexts where this
 *                            widget is allowed (default: array('all'))
 * @param bool   $multiple    Whether or not multiple instances of this widget
 *                            are allowed in a single layout (default: false)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_widget_type($handler, $name, $description, $context = array('all'), $multiple = false) {
	if (is_string($context)) {
		elgg_deprecated_notice('context parameters for elgg_register_widget_type() should be passed as an array())', 1.9);
		$context = explode(",", $context);
	} elseif (empty($context)) {
		$context = array('all');
	}

	return _elgg_services()->widgets->registerType($handler, $name, $description, $context, $multiple);
}

/**
 * Remove a widget type
 *
 * @param string $handler The identifier for the widget
 *
 * @return bool true if handler was found as unregistered
 * @since 1.8.0
 */
function elgg_unregister_widget_type($handler) {
	return _elgg_services()->widgets->unregisterType($handler);
}

/**
 * Has a widget type with the specified handler been registered
 *
 * @param string $handler The widget handler identifying string
 *
 * @return bool Whether or not that widget type exists
 * @since 1.8.0
 */
function elgg_is_widget_type($handler) {
	return _elgg_services()->widgets->validateType($handler);
}

/**
 * Get the widget types for a context
 *
 * The widget types are \stdClass objects.
 *
 * @param string $context The widget context or empty string for current context
 * @param bool   $exact   Only return widgets registered for this context (false)
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_widget_types($context = "", $exact = false) {
	return _elgg_services()->widgets->getTypes($context, $exact);
}

/**
 * Set the widget title on ajax return from save action
 * 
 * @param string $hook    Hook name
 * @param string $type    Hook type
 * @param array  $results Array to be encoded as json
 * @param array  $params  Parameters about the request
 * @return array|null
 * @access private
 */
function _elgg_widgets_set_ajax_title($hook, $type, $results, $params) {
	if ($params['action'] == 'widgets/save') {
		// @todo Elgg makes ajax so difficult - no other way to add data to output
		$widget = get_entity(get_input('guid'));
		if ($widget && $widget->title) {
			$results['title'] = $widget->title;
			return $results;
		}
	}
}

/**
 * Function to initialize widgets functionality
 *
 * @return void
 * @access private
 */
function _elgg_widgets_init() {
	elgg_register_action('widgets/save');
	elgg_register_action('widgets/add');
	elgg_register_action('widgets/move');
	elgg_register_action('widgets/delete');
	elgg_register_action('widgets/upgrade', '', 'admin');

	elgg_register_plugin_hook_handler('output', 'ajax', '_elgg_widgets_set_ajax_title');
}

/**
 * Gets a list of events to create default widgets for and
 * register menu items for default widgets with the admin section.
 *
 * A plugin that wants to register a new context for default widgets should
 * register for the plugin hook 'get_list', 'default_widgets'. The handler 
 * can register the new type of default widgets by adding an associate array to
 * the return value array like this:
 * array(
 *     'name' => elgg_echo('profile'),
 *     'widget_context' => 'profile',
 *     'widget_columns' => 3,
 *
 *     'event' => 'create',
 *     'entity_type' => 'user',
 *     'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
 * );
 *
 * The first set of keys define information about the new type of default
 * widgets and the second set determine what event triggers the creation of the
 * new widgets.
 *
 * @return void
 * @access private
 */
function _elgg_default_widgets_init() {
	global $CONFIG;
	$default_widgets = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());

	$CONFIG->default_widget_info = $default_widgets;

	if ($default_widgets) {
		elgg_register_admin_menu_item('configure', 'default_widgets', 'appearance');

		// override permissions for creating widget on logged out / just created entities
		elgg_register_plugin_hook_handler('container_permissions_check', 'object', '_elgg_default_widgets_permissions_override');

		// only register the callback once per event
		$events = array();
		foreach ($default_widgets as $info) {
			if (!is_array($info)) {
				continue;
			}
			$event = elgg_extract('event', $info);
			$entity_type = elgg_extract('entity_type', $info);
			if (!$event || !$entity_type) {
				continue;
			}
			if (!isset($events[$event][$entity_type])) {
				elgg_register_event_handler($event, $entity_type, '_elgg_create_default_widgets');
				$events[$event][$entity_type] = true;
			}
		}
	}
}

/**
 * Creates default widgets
 *
 * This plugin hook handler is registered for events based on what kinds of
 * default widgets have been registered. See elgg_default_widgets_init() for
 * information on registering new default widget contexts.
 *
 * @param string $event  The event
 * @param string $type   The type of object
 * @param \ElggEntity $entity The entity being created
 * @return void
 * @access private
 */
function _elgg_create_default_widgets($event, $type, $entity) {
	$default_widget_info = elgg_get_config('default_widget_info');

	if (!$default_widget_info || !$entity) {
		return;
	}

	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	// event is already guaranteed by the hook registration.
	// need to check subtype and type.
	foreach ($default_widget_info as $info) {
		if ($info['entity_type'] == $type) {
			if ($info['entity_subtype'] == ELGG_ENTITIES_ANY_VALUE || $info['entity_subtype'] == $subtype) {

				// need to be able to access everything
				$old_ia = elgg_set_ignore_access(true);
				elgg_push_context('create_default_widgets');

				// pull in by widget context with widget owners as the site
				// not using elgg_get_widgets() because it sorts by columns and we don't care right now.
				$options = array(
					'type' => 'object',
					'subtype' => 'widget',
					'owner_guid' => elgg_get_site_entity()->guid,
					'private_setting_name' => 'context',
					'private_setting_value' => $info['widget_context'],
					'limit' => 0
				);

				$widgets = elgg_get_entities_from_private_settings($options);
				/* @var \ElggWidget[] $widgets */

				foreach ($widgets as $widget) {
					// change the container and owner
					$new_widget = clone $widget;
					$new_widget->container_guid = $entity->guid;
					$new_widget->owner_guid = $entity->guid;

					// pull in settings
					$settings = get_all_private_settings($widget->guid);

					foreach ($settings as $name => $value) {
						$new_widget->$name = $value;
					}

					$new_widget->save();
				}

				elgg_set_ignore_access($old_ia);
				elgg_pop_context();
			}
		}
	}
}

/**
 * Overrides permissions checks when creating widgets for logged out users.
 *
 * @param string $hook   The permissions hook.
 * @param string $type   The type of entity being created.
 * @param string $return Value
 * @param mixed  $params Params
 * @return true|null
 * @access private
 */
function _elgg_default_widgets_permissions_override($hook, $type, $return, $params) {
	if ($type == 'object' && $params['subtype'] == 'widget') {
		return elgg_in_context('create_default_widgets') ? true : null;
	}

	return null;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_widgets_init');
	$events->registerHandler('ready', 'system', '_elgg_default_widgets_init');
};
