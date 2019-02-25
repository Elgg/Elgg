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
 * @param string|array $handler     An array of options or the identifier for the widget handler
 * @param string       $name        The name of the widget type
 * @param string       $description A description for the widget type
 * @param array        $context     An array of contexts where this widget is allowed
 * @param bool         $multiple    Whether or not multiple instances of this widget
 *                                  are allowed in a single layout (default: false)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_widget_type($handler, $name = null, $description = null, $context = [], $multiple = false) {
	if (is_array($handler)) {
		$definition = \Elgg\WidgetDefinition::factory($handler);
	} else {
		$definition = \Elgg\WidgetDefinition::factory([
			'id' => $handler,
			'name' => $name,
			'description' => $description,
			'context' => $context,
			'multiple' => $multiple,
		]);
	}

	return _elgg_services()->widgets->registerType($definition);
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
 * @param string      $handler   The widget handler identifying string
 * @param string      $context   Optional context to check
 * @param \ElggEntity $container Optional limit widget definitions to a container
 *
 * @return bool Whether or not that widget type exists
 * @since 1.8.0
 */
function elgg_is_widget_type($handler, $context = null, \ElggEntity $container = null) {
	return _elgg_services()->widgets->validateType($handler, $context, $container);
}

/**
 * Get the widget types for a context
 *
 * If passing $context as an associative array you the following can be used:
 * array (
 *     'context' => string (defaults to ''),
 *     'exact'   => bool (defaults to false),
 *     'container' => \ElggEntity (defaults to null)
 * )
 * The contents of the array will be passed to the handlers:widgets hook.
 *
 * @param array|string $context An associative array of options or the widget context
 *
 * @return \Elgg\WidgetDefinition[]
 * @since 1.8.0
 */
function elgg_get_widget_types($context = "") {
	if (is_array($context)) {
		$params = $context;
	} else {
		$params = [
			'context' => $context,
			'container' => null,
		];
	}
	return _elgg_services()->widgets->getTypes($params);
}

/**
 * Returns widget URLS used in widget titles
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $result URL
 * @param array  $params Parameters
 * @return string|null
 * @access private
 */
function _elgg_widgets_widget_urls($hook, $type, $result, $params) {
	$widget = elgg_extract('entity', $params);
	if (!($widget instanceof \ElggWidget)) {
		return;
	}
	
	switch ($widget->handler) {
		case 'content_stats':
			return 'admin/statistics';
		case 'cron_status':
			return 'admin/cron';
		case 'new_users':
			return 'admin/users/newest';
		case 'online_users':
			return 'admin/users/online';
	}
}

/**
 * Function to initialize widgets functionality
 *
 * @return void
 * @access private
 */
function _elgg_widgets_init() {
	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_widgets_widget_urls');
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
	$default_widgets = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, []);

	_elgg_config()->default_widget_info = $default_widgets;

	if (empty($default_widgets)) {
		return;
	}

	elgg_register_menu_item('page', [
		'name' => 'default_widgets',
		'text' => elgg_echo('admin:configure_utilities:default_widgets'),
		'href' => 'admin/configure_utilities/default_widgets',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
		'context' => 'admin',
	]);

	// override permissions for creating widget on logged out / just created entities
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '_elgg_default_widgets_permissions_override');

	// only register the callback once per event
	$events = [];
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

/**
 * Creates default widgets
 *
 * This plugin hook handler is registered for events based on what kinds of
 * default widgets have been registered. See elgg_default_widgets_init() for
 * information on registering new default widget contexts.
 *
 * @param string      $event  The event
 * @param string      $type   The type of object
 * @param \ElggEntity $entity The entity being created
 * @return void
 * @access private
 */
function _elgg_create_default_widgets($event, $type, $entity) {
	$default_widget_info = _elgg_config()->default_widget_info;

	if (empty($default_widget_info) || !$entity) {
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
		$old_ia = elgg_set_ignore_access(true);
		elgg_push_context('create_default_widgets');

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

		elgg_set_ignore_access($old_ia);
		elgg_pop_context();
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

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_widgets_init');
	$events->registerHandler('ready', 'system', '_elgg_default_widgets_init');
};
