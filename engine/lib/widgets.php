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
	return _elgg_services()->widgets->get($user_guid, $context);
}

/**
 * Output a single column of widgets.
 *
 * @param ElggUser $user        The owner user entity.
 * @param string   $context     The context (profile, dashboard, etc.)
 * @param int      $column      Which column to output.
 * @param bool     $show_access Show the access control (true by default)
 */
function elgg_view_widgets($user, $context, $column, $show_access = true) {
	return _elgg_services()->widgets->view($user, $context, $column, $show_access);
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
	return _elgg_services()->widgets->create($owner_guid, $handler, $context, $access_id);
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
 * Regsiter a widget type
 *
 * This should be called by plugins in their init function.
 *
 * @param string $handler     The identifier for the widget handler
 * @param string $name        The name of the widget type
 * @param string $description A description for the widget type
 * @param array $context      An array of contexts where this
 *                            widget is allowed (default: array('all'))
 * @param bool   $multiple    Whether or not multiple instances of this widget
 *                            are allowed in a single layout (default: false)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_widget_type($handler, $name, $description, $context = array('all'), $multiple = false) {
	return _elgg_services()->widgets->registerType($handler, $name, $description, $context, $multiple);
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
 * The widget types are stdClass objects.
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
 * Regsiter entity of object, widget as ElggWidget objects
 *
 * @return void
 * @access private
 */
function elgg_widget_run_once() {
	add_subtype("object", "widget", "ElggWidget");
}

/**
 * Function to initialize widgets functionality
 *
 * @return void
 * @access private
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
function elgg_default_widgets_init() {
	_elgg_services()->widgets->defaultWidgetsInit();
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
 * @param ElggEntity $entity The entity being created
 * @return void
 * @access private
 */
function elgg_create_default_widgets($event, $type, $entity) {
	return _elgg_services()->widgets->createDefault($event, $type, $entity);
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
function elgg_default_widgets_permissions_override($hook, $type, $return, $params) {
	return _elgg_services()->widgets->defaultWidgetsPermissionsOverride($hook, $type, $return, $params);
}

elgg_register_event_handler('init', 'system', 'elgg_widgets_init');
// register default widget hooks from plugins
elgg_register_event_handler('ready', 'system', 'elgg_default_widgets_init');
