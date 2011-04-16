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
 * @param int    $user_guid The owner user GUID
 * @param string $context   The context (profile, dashboard, etc)
 *
 * @return array An 2D array of ElggWidget objects
 * @since 1.8.0
 */
function elgg_get_widgets($user_guid, $context) {
	$options = array(
		'type' => 'object',
		'subtype' => 'widget',
		'owner_guid' => $user_guid,
		'private_setting_name' => 'context',
		'private_setting_value' => $context,
		'limit' => 0
	);
	$widgets = elgg_get_entities_from_private_settings($options);
	if (!$widgets) {
		return array();
	}

	$sorted_widgets = array();
	foreach ($widgets as $widget) {
		if (!isset($sorted_widgets[(int)$widget->column])) {
			$sorted_widgets[(int)$widget->column] = array();
		}
		$sorted_widgets[(int)$widget->column][$widget->order] = $widget;
	}

	foreach ($sorted_widgets as $col => $widgets) {
		ksort($sorted_widgets[$col]);
	}

	return $sorted_widgets;
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
	if (empty($owner_guid) || empty($handler) || !elgg_is_widget_type($handler)) {
		return false;
	}

	$owner = get_entity($owner_guid);
	if (!$owner) {
		return false;
	}

	$widget = new ElggWidget;
	$widget->owner_guid = $owner_guid;
	$widget->container_guid = $owner_guid; // @todo - will this work for group widgets
	if (isset($access_id)) {
		$widget->access_id = $access_id;
	} else {
		$widget->access_id = get_default_access();
	}

	if (!$widget->save()) {
		return false;
	}

	// private settings cannot be set until ElggWidget saved
	$widget->handler = $handler;
	$widget->context = $context;

	return $widget->getGUID();
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

	$user = get_entity((int)$user_guid);
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}

	$return = false;
	if (elgg_is_admin_logged_in()) {
		$return = true;
	}
	if (elgg_get_page_owner_guid() == $user->guid) {
		$return = true;
	}

	$params = array(
		'user' => $user,
		'context' => $context,
		'page_owner' => elgg_get_page_owner_entity()
	);
	return elgg_trigger_plugin_hook('permissions_check', 'widget_layout', $params, $return);
}

/**
 * Regsiter a widget type
 *
 * This should be called by plugins in their init function.
 *
 * @param string $handler     The identifier for the widget handler
 * @param string $name        The name of the widget type
 * @param string $description A description for the widget type
 * @param string $context     A comma-separated list of contexts where this
 *                            widget is allowed (default: 'all')
 * @param bool   $multiple    Whether or not multiple instances of this widget
 *                            are allowed in a single layout (default: false)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_widget_type($handler, $name, $description, $context = "all", $multiple = false) {

	if (!$handler || !$name) {
		return false;
	}

	global $CONFIG;

	if (!isset($CONFIG->widgets)) {
		$CONFIG->widgets = new stdClass;
	}
	if (!isset($CONFIG->widgets->handlers)) {
		$CONFIG->widgets->handlers = array();
	}

	$handlerobj = new stdClass;
	$handlerobj->name = $name;
	$handlerobj->description = $description;
	$handlerobj->context = explode(",", $context);
	$handlerobj->multiple = $multiple;

	$CONFIG->widgets->handlers[$handler] = $handlerobj;

	return true;
}

/**
 * Remove a widget type
 *
 * @param string $handler The identifier for the widget
 *
 * @return void
 * @since 1.8.0
 */
function elgg_unregister_widget_type($handler) {
	global $CONFIG;

	if (!isset($CONFIG->widgets)) {
		return;
	}

	if (!isset($CONFIG->widgets->handlers)) {
		return;
	}

	if (isset($CONFIG->widgets->handlers[$handler])) {
		unset($CONFIG->widgets->handlers[$handler]);
	}
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
	global $CONFIG;

	if (!empty($CONFIG->widgets) &&
		!empty($CONFIG->widgets->handlers) &&
		is_array($CONFIG->widgets->handlers) &&
		array_key_exists($handler, $CONFIG->widgets->handlers)) {

		return true;
	}

	return false;
}

/**
 * Get the widget types for a context
 *
 * The widget types are stdClass objects.
 *
 * @param string $context The widget context or empty string for current context
 * @param bool   $exact   Only return widgets registered for this context (false)
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_widget_types($context = "", $exact = false) {
	global $CONFIG;

	if (empty($CONFIG->widgets) ||
		empty($CONFIG->widgets->handlers) ||
		!is_array($CONFIG->widgets->handlers)) {
		// no widgets
		return array();
	}

	if (!$context) {
		$context = elgg_get_context();
	}

	$widgets = array();
	foreach ($CONFIG->widgets->handlers as $key => $handler) {
		if ($exact) {
			if (in_array($context, $handler->context)) {
				$widgets[$key] = $handler;
			}
		} else {
			if (in_array('all', $handler->context) || in_array($context, $handler->context)) {
				$widgets[$key] = $handler;
			}
		}
	}

	return $widgets;
}

/**
 * Regsiter entity of object, widget as ElggWidget objects
 *
 * @return void
 */
function elgg_widget_run_once() {
	add_subtype("object", "widget", "ElggWidget");
}

/**
 * Function to initialize widgets functionality
 *
 * @return void
 */
function elgg_widgets_init() {
	elgg_register_action('widgets/save');
	elgg_register_action('widgets/add');
	elgg_register_action('widgets/move');
	elgg_register_action('widgets/delete');
	elgg_register_action('widgets/upgrade', '', 'admin');

	run_function_once("elgg_widget_run_once");
}

/**
 * Gets a list of events to create default widgets for and
 * register menu items for default widgets with the admin section.
 *
 * @return void
 */
function elgg_default_widgets_init() {
	global $CONFIG;
	$default_widgets = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());

	$CONFIG->default_widget_info = $default_widgets;

	if ($default_widgets) {
		elgg_register_admin_menu_item('configure', 'default_widgets', 'appearance');

		// override permissions for creating widget on logged out / just created entities
		elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'elgg_default_widgets_permissions_override');

		foreach ($default_widgets as $info) {
			elgg_register_event_handler($info['event'], $info['entity_type'], 'elgg_default_widgets_hook');
		}
	}
}

/**
 * Checks for plugins who have registered default widgets and
 * hooks into events to save.
 *
 * @param string $event  The event
 * @param string $type   The type of object
 * @param object $object The object
 * @return null
 */
function elgg_default_widgets_hook($event, $type, $object) {
	$default_widget_info = elgg_get_config('default_widget_info');

	if (!$default_widget_info) {
		return null;
	}

	$subtype = $object->getSubtype();

	// event is already guaranteed by the hook registration.
	// need to check subtype and type.
	foreach ($default_widget_info as $temp) {
		if ($temp['entity_type'] == $type && $temp['entity_subtype'] == $subtype) {
			$info = $temp;
			break;
		}
	}

	// need to be able to access everything
	$old_ia = elgg_get_ignore_access(true);
	elgg_push_context('create_default_widgets');

	// pull in by widget context with widget owners as the site
	// not using elgg_get_widgets() because it sorts by columns and we don't care right now.
	$options = array(
		'type' => 'object',
		'subtype' => 'widget',
		'owner_guid' => elgg_get_site_entity()->guid,
		'private_setting_name' => 'context',
		'private_setting_value' => $info['context'],
		'limit' => 0
	);

	$widgets = elgg_get_entities_from_private_settings($options);

	foreach ($widgets as $widget) {
		// change the container and owner
		$new_widget = clone $widget;
		$new_widget->container_guid = $object->guid;
		$new_widget->owner_guid = $object->guid;

		// pull in settings
		$settings = get_all_private_settings($widget->guid);

		foreach ($settings as $name => $value) {
			$new_widget->$name = $value;
		}

		$new_widget->save();
	}

	elgg_get_ignore_access($old_ia);
	elgg_pop_context();

	// failure here shouldn't stop the event.
	return null;
}

/**
 * Overrides permissions checks when creating widgets for logged out users.
 *
 * @param string $hook   The permissions hook.
 * @param string $type   The type of entity being created.
 * @param string $return Value
 * @param mixed  $params Params
 * @return true|null
 */
function elgg_default_widgets_permissions_override($hook, $type, $return, $params) {
	if ($type == 'object' && $params['subtype'] == 'widget') {
		return elgg_in_context('create_default_widgets') ? true : null;
	}

	return null;
}

elgg_register_event_handler('init', 'system', 'elgg_widgets_init');
// register default widget hooks from plugins
elgg_register_event_handler('ready', 'system', 'elgg_default_widgets_init');