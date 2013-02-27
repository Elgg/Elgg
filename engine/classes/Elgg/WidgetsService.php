<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @since 1.9.0
 * @access private
 */
class Elgg_WidgetsService {
	
	/**
	 * @var stdClass
	 */
	private $widgets;
	
	/**
	 * @see Elgg_WidgetsService::get()
	 * @var array
	 */
	private $widget_cache = array();
	
	/**
	 * @var array
	 */
	private $default_widget_info;
	
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
	 * @since 1.9.0
	 */
	function get($user_guid, $context) {
		$widget_cache_key = "$context-$user_guid";
	
		if (isset($this->widget_cache[$widget_cache_key])) {
			return $this->widget_cache[$widget_cache_key];
		}
	
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
	
		$this->widget_cache[$widget_cache_key] = $sorted_widgets;
	
		return $sorted_widgets;
	}
	
	/**
	 * Output a single column of widgets.
	 *
	 * @param ElggUser $user        The owner user entity.
	 * @param string   $context     The context (profile, dashboard, etc.)
	 * @param int      $column      Which column to output.
	 * @param bool     $show_access Show the access control (true by default)
	 * @since 1.9.0
	 */
	function view($user, $context, $column, $show_access = true) {
		$widgets = elgg_get_widgets($user->guid, $context);
		$column_widgets = $widgets[$column];
	
		$column_html = "<div class=\"elgg-widgets\" id=\"elgg-widget-col-$column\">";
		if (count($column_widgets) > 0) {
			foreach ($column_widgets as $widget) {
				if ($this->validateType($widget->handler)) {
					$column_html .= elgg_view_entity($widget, array('show_access' => $show_access));
				}
			}
		}
		$column_html .= '</div>';
	
		return $column_html;
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
	 * @since 1.9.0
	 */
	function create($owner_guid, $handler, $context, $access_id = null) {
		if (empty($owner_guid) || empty($handler) || !$this->validateType($handler)) {
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
	 * @since 1.9.0
	 */
	function canEditLayout($context, $user_guid = 0) {
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
	 * @param array $context      An array of contexts where this
	 *                            widget is allowed (default: array('all'))
	 * @param bool   $multiple    Whether or not multiple instances of this widget
	 *                            are allowed in a single layout (default: false)
	 *
	 * @return bool
	 * @since 1.9.0
	 */
	function registerType($handler, $name, $description, $context = array('all'), $multiple = false) {
		if (!$handler || !$name) {
			return false;
		}

		if (!isset($this->widgets)) {
			$this->widgets = new stdClass;
			elgg_set_config('widgets', $this->widgets);//backward compatibility
		}
		if (!isset($this->widgets->handlers)) {
			$this->widgets->handlers = array();
		}
	
		$handlerobj = new stdClass;
		$handlerobj->name = $name;
		$handlerobj->description = $description;
		if (is_string($context)) {
			elgg_deprecated_notice('context parameters for elgg_register_widget_type() should be passed as an array())', 1.9);
			$context = explode(",", $context);
		} elseif (empty($context)) {
			$context = array('all');
		}
		$handlerobj->context = $context;
		$handlerobj->multiple = $multiple;
	
		$this->widgets->handlers[$handler] = $handlerobj;
	
		return true;
	}
	
	/**
	 * Remove a widget type
	 *
	 * @param string $handler The identifier for the widget
	 *
	 * @return void
	 * @since 1.9.0
	 */
	function unregisterType($handler) {
		if (!isset($this->widgets)) {
			return;
		}
	
		if (!isset($this->widgets->handlers)) {
			return;
		}
	
		if (isset($this->widgets->handlers[$handler])) {
			unset($this->widgets->handlers[$handler]);
		}
	}
	
	/**
	 * Has a widget type with the specified handler been registered
	 *
	 * @param string $handler The widget handler identifying string
	 *
	 * @return bool Whether or not that widget type exists
	 * @since 1.9.0
	 */
	function validateType($handler) {
		if (!empty($this->widgets) &&
				!empty($this->widgets->handlers) &&
				is_array($this->widgets->handlers) &&
				array_key_exists($handler, $this->widgets->handlers)) {
	
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
	 * @since 1.9.0
	 */
	function getTypes($context = "", $exact = false) {
		if (empty($this->widgets) ||
				empty($this->widgets->handlers) ||
				!is_array($this->widgets->handlers)) {
			// no widgets
			return array();
		}
	
		if (!$context) {
			$context = elgg_get_context();
		}
	
		$widgets = array();
		foreach ($this->widgets->handlers as $key => $handler) {
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
	 * @since 1.9.0
	 */
	function defaultWidgetsInit() {
		$default_widgets = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());
	
		$this->default_widget_info = $default_widgets;
	
		if ($default_widgets) {
			elgg_register_admin_menu_item('configure', 'default_widgets', 'appearance');
	
			// override permissions for creating widget on logged out / just created entities
			elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'elgg_default_widgets_permissions_override');
	
			// only register the callback once per event
			$events = array();
			foreach ($default_widgets as $info) {
				$events[$info['event'] . ',' . $info['entity_type']] = $info;
			}
			foreach ($events as $info) {
				elgg_register_event_handler($info['event'], $info['entity_type'], 'elgg_create_default_widgets');
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
	 * @param ElggEntity $entity The entity being created
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	function createDefault($event, $type, $entity) {
		if (!$this->default_widget_info || !$entity) {
			return;
		}
	
		$type = $entity->getType();
		$subtype = $entity->getSubtype();
	
		// event is already guaranteed by the hook registration.
		// need to check subtype and type.
		foreach ($this->default_widget_info as $info) {
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
					/* @var ElggWidget[] $widgets */
	
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
	 * @since 1.9.0
	 */
	function defaultWidgetsPermissionsOverride($hook, $type, $return, $params) {
		if ($type == 'object' && $params['subtype'] == 'widget') {
			return elgg_in_context('create_default_widgets') ? true : null;
		}
	
		return null;
	}
	
	/**
	 * @return stdClass
	 */
	function getWidgets() {
		return $this->widgets;
	}
}
