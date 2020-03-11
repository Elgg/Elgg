<?php
/**
 * Elgg widgets library.
 * Contains code for handling widgets.
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
 * @internal
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
			elgg_register_event_handler($event, $entity_type, 'Elgg\Widgets\CreateDefaultWidgetsHandler');
			$events[$event][$entity_type] = true;
		}
	}
}
