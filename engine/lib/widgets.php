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
 * $widgets = elgg_get_widgets(get_loggedin_userid(), 'dashboard');
 * $first_column_widgets = $widgets[1];
 *
 * @param int    $user_guid The owner user GUID
 * @param string $context   The context (profile, dashboard, etc)
 * 
 * @return array|false An 2D array of ElggWidget objects or false
 * @since 1.8.0
 */
function elgg_get_widgets($user_guid, $context) {
	$options = array(
		'type' => 'object',
		'subtype' => 'widget',
		'owner_guid' => $user_guid,
		'private_setting_name' => 'context',
		'private_setting_value' => $context
	);
	$widgets = elgg_get_entities_from_private_settings($options);
	if (!$widgets) {
		return false;
	}

	$sorted_widgets = array();
	foreach ($widgets as $widget) {
		if (!isset($sorted_widgets[$widget->column])) {
			$sorted_widgets[$widget->column] = array();
		}
		$sorted_widgets[$widget->column][$widget->order] = $widget;
	}

	foreach ($sorted_widgets as $col => $widgets) {
		ksort($sorted_widgets[$col]);
	}

	return $sorted_widgets;
}

/**
 * Create a new widget instance
 *
 * @param int    $entity_guid GUID of entity that owns this widget
 * @param string $handler     The handler for this widget
 * @param int    $access_id   If not specified, it is set to the default access level
 * 
 * @return int|false Widget GUID or false on failure
 * @since 1.8
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
		$user = get_loggedin_user();
	}

	$return = false;
	if (isadminloggedin()) {
		$return = true;
	}
	if (elgg_get_page_owner_guid() == $user->guid) {
		$return = true;
	}

	$params = array(
		'user' => $user,
		'context' => $context,
		'page_owner' => elgg_get_page_owner()
	);
	return elgg_trigger_plugin_hook('permissions_check', 'widget_layout', $params, $return);
}

/**
 * Add a widget type
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
function elgg_add_widget_type($handler, $name, $description, $context = "all", $multiple = false) {

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
function elgg_remove_widget_type($handler) {
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
 * @param string context The widget context or empty string for current context
 * 
 * @return array
 * @since 1.8.0
 */
function elgg_get_widget_types($context = "") {
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
		if (in_array('all', $handler->context) || in_array($context, $handler->context)) {
			$widgets[$key] = $handler;
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
	register_action('widgets/save');
	register_action('widgets/add');
	register_action('widgets/move');
	register_action('widgets/delete');

	run_function_once("elgg_widget_run_once");
}

elgg_register_event_handler('init', 'system', 'elgg_widgets_init');



/**
 * When given a widget entity and a new requested location, saves the new location
 * and also provides a sensible ordering for all widgets in that column
 *
 * @param ElggObject $widget The widget entity
 * @param int        $order  The order within the column
 * @param int        $column The column (1, 2 or 3)
 *
 * @return bool Depending on success
 * @deprecated 1.8 use ElggWidget::move()
 */
function save_widget_location(ElggObject $widget, $order, $column) {
	elgg_deprecated_notice('save_widget_location() is deprecated', 1.8);
	if ($widget instanceof ElggObject) {
		if ($widget->subtype == "widget") {
			// If you can't move the widget, don't save a new location
			if (!$widget->draggable) {
				return false;
			}

			// Sanitise the column value
			if ($column != 1 || $column != 2 || $column != 3) {
				$column = 1;
			}

			$widget->column = (int) $column;

			$ordertmp = array();
			$params = array(
				'context' => $widget->context,
				'column' => $column,
			);

			if ($entities = get_entities_from_metadata_multi($params, 'object', 'widget')) {
				foreach ($entities as $entity) {
					$entityorder = $entity->order;
					if ($entityorder < $order) {
						$ordertmp[$entityorder] = $entity;
					}
					if ($entityorder >= $order) {
						$ordertmp[$entityorder + 10000] = $entity;
					}
				}
			}

			$ordertmp[$order] = $widget;
			ksort($ordertmp);

			$orderticker = 10;
			foreach ($ordertmp as $orderval => $entity) {
				$entity->order = $orderticker;
				$orderticker += 10;
			}

			return true;
		} else {
			register_error($widget->subtype);
		}

	}

	return false;
}

/**
 * Get widgets for a particular context and column, in order of display
 *
 * @param int    $user_guid The owner user GUID
 * @param string $context   The context (profile, dashboard etc)
 * @param int    $column    The column (1 or 2)
 *
 * @return array|false An array of widget ElggObjects, or false
 * @deprecated 1.8 Use elgg_get_widgets()
 */
function get_widgets($user_guid, $context, $column) {
	elgg_deprecated_notice('get_widgets is depecated for elgg_get_widgets', 1.8);
	$params = array(
		'column' => $column,
		'context' => $context
	);
	$widgets = get_entities_from_private_setting_multi($params, "object",
		"widget", $user_guid, "", 10000);

	if ($widgets) {
		$widgetorder = array();
		foreach ($widgets as $widget) {
			$order = $widget->order;
			while (isset($widgetorder[$order])) {
				$order++;
			}
			$widgetorder[$order] = $widget;
		}

		ksort($widgetorder);

		return $widgetorder;
	}

	return false;
}

/**
 * Add a new widget instance
 *
 * @param int    $entity_guid GUID of entity that owns this widget
 * @param string $handler     The handler for this widget
 * @param string $context     The page context for this widget
 * @param int    $order       The order to display this widget in
 * @param int    $column      The column to display this widget in (1, 2 or 3)
 * @param int    $access_id   If not specified, it is set to the default access level
 *
 * @return int|false Widget GUID or false on failure
 * @deprecated 1.8 use elgg_create_widget()
 */
function add_widget($entity_guid, $handler, $context, $order = 0, $column = 1, $access_id = null) {
	elgg_deprecated_notice('add_widget has been deprecated for elgg_create_widget', 1.8);
	if (empty($entity_guid) || empty($context) || empty($handler) || !widget_type_exists($handler)) {
		return false;
	}

	if ($entity = get_entity($entity_guid)) {
		$widget = new ElggWidget;
		$widget->owner_guid = $entity_guid;
		$widget->container_guid = $entity_guid;
		if (isset($access_id)) {
			$widget->access_id = $access_id;
		} else {
			$widget->access_id = get_default_access();
		}

		$guid = $widget->save();

		// private settings cannot be set until ElggWidget saved
		$widget->handler = $handler;
		$widget->context = $context;
		$widget->column = $column;
		$widget->order = $order;

		return $guid;
	}

	return false;
}

/**
 * Define a new widget type
 *
 * @param string $handler     The identifier for the widget handler
 * @param string $name        The name of the widget type
 * @param string $description A description for the widget type
 * @param string $context     A comma-separated list of contexts where this
 *                            widget is allowed (default: 'all')
 * @param bool   $multiple    Whether or not multiple instances of this widget
 *                            are allowed on a single dashboard (default: false)
 * @param string $positions   A comma-separated list of positions on the page
 *                            (side or main) where this widget is allowed (default: "side,main")
 *
 * @return bool Depending on success
 * @deprecated 1.8
 */
function add_widget_type($handler, $name, $description, $context = "all",
$multiple = false, $positions = "side,main") {
	elgg_deprecated_notice("add_widget_type deprecated for elgg_add_widget_type", 1.8);

	return elgg_add_widget_type($handler, $name, $description, $context, $multiple);
}

/**
 * Remove a widget type
 *
 * @param string $handler The identifier for the widget handler
 *
 * @return void
 * @since 1.7.1
 * @deprecated 1.8
 */
function remove_widget_type($handler) {
	elgg_deprecated_notice("remove_widget_type deprecated for elgg_remove_widget_type", 1.8);
	return elgg_remove_widget_type($handler);
}

/**
 * Determines whether or not widgets with the specified handler have been defined
 *
 * @param string $handler The widget handler identifying string
 *
 * @return bool Whether or not those widgets exist
 * @deprecated 1.8
 */
function widget_type_exists($handler) {
	elgg_deprecated_notice("widget_type_exists deprecated for elgg_is_widget_type", 1.8);
	return elgg_is_widget_type($handler);
}

/**
 * Returns an array of stdClass objects representing the defined widget types
 *
 * @return array A list of types defined (if any)
 * @deprecated 1.8
 */
function get_widget_types() {
	elgg_deprecated_notice("get_widget_types deprecrated for elgg_get_widget_types", 1.8);
	return elgg_get_widget_types();
}

/**
 * Saves a widget's settings (by passing an array of
 * (name => value) pairs to save_{$handler}_widget)
 *
 * @param int   $widget_guid The GUID of the widget we're saving to
 * @param array $params      An array of name => value parameters
 *
 * @return bool
 * @deprecated 1.8
 */
function save_widget_info($widget_guid, $params) {
	elgg_deprecated_notice("save_widget_info() is deprecated for elgg_save_widget_settings", 1.8);
	if ($widget = get_entity($widget_guid)) {

		$subtype = $widget->getSubtype();

		if ($subtype != "widget") {
			return false;
		}
		$handler = $widget->handler;
		if (empty($handler) || !widget_type_exists($handler)) {
			return false;
		}

		if (!$widget->canEdit()) {
			return false;
		}

		// Save the params to the widget
		if (is_array($params) && sizeof($params) > 0) {
			foreach ($params as $name => $value) {

				if (!empty($name) && !in_array($name, array(
					'guid', 'owner_guid', 'site_guid'
				))) {
					if (is_array($value)) {
						// @todo Handle arrays securely
						$widget->setMetaData($name, $value, "", true);
					} else {
						$widget->$name = $value;
					}
				}
			}
			$widget->save();
		}

		$function = "save_{$handler}_widget";
		if (is_callable($function)) {
			return $function($params);
		}

		return true;
	}

	return false;
}

/**
 * Reorders the widgets from a widget panel
 *
 * @param string $panelstring1 String of guids of ElggWidget objects separated by ::
 * @param string $panelstring2 String of guids of ElggWidget objects separated by ::
 * @param string $panelstring3 String of guids of ElggWidget objects separated by ::
 * @param string $context      Profile or dashboard
 * @param int    $owner        Owner guid
 *
 * @return void
 * @deprecated 1.8
 */
function reorder_widgets_from_panel($panelstring1, $panelstring2, $panelstring3, $context, $owner) {
	elgg_deprecated_notice("reorder_widgets_from_panel() is deprecated", 1.8);
	$return = true;

	$mainwidgets = explode('::', $panelstring1);
	$sidewidgets = explode('::', $panelstring2);
	$rightwidgets = explode('::', $panelstring3);

	$handlers = array();
	$guids = array();

	if (is_array($mainwidgets) && sizeof($mainwidgets) > 0) {
		foreach ($mainwidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[1][] = $widget;
			} else {
				$handlers[1][] = $widget;
			}
		}
	}
	if (is_array($sidewidgets) && sizeof($sidewidgets) > 0) {
		foreach ($sidewidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[2][] = $widget;
			} else {
				$handlers[2][] = $widget;
			}

		}
	}
	if (is_array($rightwidgets) && sizeof($rightwidgets) > 0) {
		foreach ($rightwidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[3][] = $widget;
			} else {
				$handlers[3][] = $widget;
			}

		}
	}

	// Reorder existing widgets or delete ones that have vanished
	foreach (array(1, 2, 3) as $column) {
		if ($dbwidgets = get_widgets($owner, $context, $column)) {

			foreach ($dbwidgets as $dbwidget) {
				if (in_array($dbwidget->getGUID(), $guids[1])
				|| in_array($dbwidget->getGUID(), $guids[2]) || in_array($dbwidget->getGUID(), $guids[3])) {

					if (in_array($dbwidget->getGUID(), $guids[1])) {
						$pos = array_search($dbwidget->getGUID(), $guids[1]);
						$col = 1;
					} else if (in_array($dbwidget->getGUID(), $guids[2])) {
						$pos = array_search($dbwidget->getGUID(), $guids[2]);
						$col = 2;
					} else {
						$pos = array_search($dbwidget->getGUID(), $guids[3]);
						$col = 3;
					}
					$pos = ($pos + 1) * 10;
					$dbwidget->column = $col;
					$dbwidget->order = $pos;
				} else {
					$dbguid = $dbwidget->getGUID();
					if (!$dbwidget->delete()) {
						$return = false;
					} else {
						// Remove state cookie
						setcookie('widget' + $dbguid, null);
					}
				}
			}

		}
		// Add new ones
		if (sizeof($guids[$column]) > 0) {
			foreach ($guids[$column] as $key => $guid) {
				if ($guid == 0) {
					$pos = ($key + 1) * 10;
					$handler = $handlers[$column][$key];
					if (!add_widget($owner, $handler, $context, $pos, $column)) {
						$return = false;
					}
				}
			}
		}
	}

	return $return;
}

/**
 * Register a particular context for use with widgets.
 *
 * @param string $context The context we wish to enable context for
 *
 * @return void
 * @deprecated 1.8
 */
function use_widgets($context) {
	elgg_deprecated_notice("use_widgets is deprecated", 1.8);
	global $CONFIG;

	if (!isset($CONFIG->widgets)) {
		$CONFIG->widgets = new stdClass;
	}

	if (!isset($CONFIG->widgets->contexts)) {
		$CONFIG->widgets->contexts = array();
	}

	if (!empty($context)) {
		$CONFIG->widgets->contexts[] = $context;
	}
}

/**
 * Determines whether or not the current context is using widgets
 *
 * @return bool Depending on widget status
 * @deprecated 1.8
 */
function using_widgets() {
	elgg_deprecated_notice("using_widgets is deprecated", 1.8);
	global $CONFIG;

	$context = elgg_get_context();
	if (isset($CONFIG->widgets->contexts) && is_array($CONFIG->widgets->contexts)) {
		if (in_array($context, $CONFIG->widgets->contexts)) {
			return true;
		}
	}

	return false;
}

/**
 * @deprecated 1.8
 */
function display_widget(ElggObject $widget) {
	elgg_deprecated_notice("display_widget() was been deprecated. Use elgg_view_entity().", 1.8);
	return elgg_view_entity($widget);
}
