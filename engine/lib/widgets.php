<?php
/**
 * Elgg widgets library.
 * Contains code for handling widgets.
 *
 * @package Elgg.Core
 * @subpackage Widgets
 */

/**
 * Register a particular context for use with widgets.
 *
 * @param string $context The context we wish to enable context for
 *
 * @return void
 */
function use_widgets($context) {
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
 */
function using_widgets() {
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
 * When given a widget entity and a new requested location, saves the new location
 * and also provides a sensible ordering for all widgets in that column
 *
 * @param ElggObject $widget The widget entity
 * @param int        $order  The order within the column
 * @param int        $column The column (1, 2 or 3)
 *
 * @return bool Depending on success
 */
function save_widget_location(ElggObject $widget, $order, $column) {
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
 */
function get_widgets($user_guid, $context, $column) {
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
 * Get widgets for a particular context in order of display
 *
 * @param int    $user_guid The owner user GUID
 * @param string $context   The context (profile, dashboard, etc)
 *
 * @return array|false An array of widget ElggObjects, or false
 */
function elgg_get_widgets($user_guid, $context) {
	// @todo implement elgg_get_entities_from_private_settings() first
	return false;
}

/**
 * @deprecated 1.8
 */
function display_widget(ElggObject $widget) {
	elgg_deprecated_notice("display_widget() was been deprecated. Use elgg_view_entity().", 1.8);
	return elgg_view_entity($widget);
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
 * @return bool Depending on success
 */
function add_widget($entity_guid, $handler, $context, $order = 0, $column = 1, $access_id = null) {
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

		if (!$widget->save()) {
			return false;
		}

		$widget->handler = $handler;
		$widget->context = $context;
		$widget->column = $column;
		$widget->order = $order;

		// save_widget_location($widget, $order, $column);
		return true;
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
 */
function add_widget_type($handler, $name, $description, $context = "all",
$multiple = false, $positions = "side,main") {

	if (!empty($handler) && !empty($name)) {
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
		$handlerobj->positions = explode(",", $positions);

		$CONFIG->widgets->handlers[$handler] = $handlerobj;

		return true;
	}

	return false;
}

/**
 * Remove a widget type
 *
 * @param string $handler The identifier for the widget handler
 *
 * @return void
 * @since 1.7.1
 */
function remove_widget_type($handler) {
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
 * Determines whether or not widgets with the specified handler have been defined
 *
 * @param string $handler The widget handler identifying string
 *
 * @return bool Whether or not those widgets exist
 */
function widget_type_exists($handler) {
	global $CONFIG;

	if (!empty($CONFIG->widgets)
		&& !empty($CONFIG->widgets->handlers)
		&& is_array($CONFIG->widgets->handlers)
		&& array_key_exists($handler, $CONFIG->widgets->handlers)) {
			return true;
	}

	return false;
}

/**
 * Returns an array of stdClass objects representing the defined widget types
 *
 * @return array A list of types defined (if any)
 */
function get_widget_types() {
	global $CONFIG;

	if (!empty($CONFIG->widgets)
	&& !empty($CONFIG->widgets->handlers)
	&& is_array($CONFIG->widgets->handlers)) {

		$context = elgg_get_context();

		foreach ($CONFIG->widgets->handlers as $key => $handler) {
			if (!in_array('all', $handler->context) &&
				!in_array($context, $handler->context)) {
					unset($CONFIG->widgets->handlers[$key]);
			}
		}

		return $CONFIG->widgets->handlers;
	}

	return array();
}

/**
 * Saves a widget's settings (by passing an array of
 * (name => value) pairs to save_{$handler}_widget)
 *
 * @param int   $widget_guid The GUID of the widget we're saving to
 * @param array $params      An array of name => value parameters
 *
 * @return bool
 */
function save_widget_info($widget_guid, $params) {
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
 */
function reorder_widgets_from_panel($panelstring1, $panelstring2, $panelstring3, $context, $owner) {
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
 * Can the user edit the widgets
 *
 * @param int $user_guid The GUID of the user or 0 for logged in user
 * @return bool
 */
function elgg_can_edit_widgets($user_guid = 0) {
	$return = false;
	if (isadminloggedin()) {
		$return = true;
	}
	if (elgg_get_page_owner_guid() == get_loggedin_userid()) {
		$return = true;
	}

	// @todo add plugin hook
	return $return;
}

/**
 * Regsiter entity of object, widget as ElggWidget objects
 *
 * @return void
 */
function widget_run_once() {
	// Register a class
	add_subtype("object", "widget", "ElggWidget");
}

/**
 * Function to initialise widgets functionality on Elgg init
 *
 * @return void
 */
function widgets_init() {
	register_action('widgets/reorder');
	register_action('widgets/save');
	register_action('widgets/add');

	// Now run this stuff, but only once
	run_function_once("widget_run_once");
}

// Register event
elgg_register_event_handler('init', 'system', 'widgets_init');

// Use widgets on the dashboard
use_widgets('dashboard');