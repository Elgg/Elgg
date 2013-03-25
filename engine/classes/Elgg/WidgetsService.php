<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Widgets
 * @since      1.9.0
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
	 * Constructor
	 */
	public function __construct() {

	}

	/**
	 * @see elgg_get_widgets
	 * @access private
	 * @since 1.9.0
	 */
	public function get($user_guid, $context) {
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
			'limit' => 0,
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
	 * @see elgg_view_widgets
	 * @access private
	 * @since 1.9.0
	 */
	public function view(ElggUser $user, $context, $column, $show_access = true) {
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
	 * @see elgg_create_widget
	 * @access private
	 * @since 1.9.0
	 */
	public function create($owner_guid, $handler, $context, $access_id = null) {
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
	 * @see elgg_can_edit_widget_layout
	 * @access private
	 * @since 1.9.0
	 */
	public function canEditLayout($context, $user_guid = 0) {
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
			'page_owner' => elgg_get_page_owner_entity(),
		);
		return elgg_trigger_plugin_hook('permissions_check', 'widget_layout', $params, $return);
	}

	/**
	 * @see elgg_register_widget_type
	 * @access private
	 * @since 1.9.0
	 */
	public function registerType($handler, $name, $description, $context = array('all'), $multiple = false) {
		if (!$handler || !$name) {
			return false;
		}

		if (!isset($this->widgets)) {
			$this->widgets = new stdClass;
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
	 * @see elgg_unregister_widget_type
	 * @access private
	 * @since 1.9.0
	 */
	public function unregisterType($handler) {
		if (!isset($this->widgets)) {
			return false;
		}

		if (!isset($this->widgets->handlers)) {
			return false;
		}

		if (isset($this->widgets->handlers[$handler])) {
			unset($this->widgets->handlers[$handler]);
			return true;
		}
		return false;
	}

	/**
	 * @see elgg_is_widget_type
	 * @access private
	 * @since 1.9.0
	 */
	public function validateType($handler) {
		if (!empty($this->widgets) &&
				!empty($this->widgets->handlers) &&
				is_array($this->widgets->handlers) &&
				array_key_exists($handler, $this->widgets->handlers)) {

			return true;
		}

		return false;
	}

	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function getAllTypes() {
		if (empty($this->widgets) ||
			empty($this->widgets->handlers) ||
			!is_array($this->widgets->handlers)) {
			// no widgets
			return array();
		}

		$widgets = array();
		foreach ($this->widgets->handlers as $key => $handler) {
			$widgets[$key] = $handler;
		}

		return $widgets;
	}

	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function getNameByType($handler) {
		if (isset($this->widgets->handlers[$handler])) {
			return $this->widgets->handlers[$handler]->name;
		}
		return false;
	}

	/**
	 * @see elgg_get_widget_types
	 * @access private
	 * @since 1.9.0
	 */
	public function getTypes($context = "", $exact = false) {
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
	 * @see elgg_default_widgets_init
	 * @access private
	 * @since 1.9.0
	 */
	public function defaultWidgetsInit() {
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
	 * @see elgg_create_default_widgets
	 * @access private
	 * @since 1.9.0
	 */
	public function createDefault($event, $type, $entity) {
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
						'limit' => 0,
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
	 * @see elgg_default_widgets_permissions_override
	 * @access private
	 * @since 1.9.0
	 */
	public function defaultWidgetsPermissionsOverride($hook, $type, $return, $params) {
		if ($type == 'object' && $params['subtype'] == 'widget') {
			return elgg_in_context('create_default_widgets') ? true : null;
		}

		return null;
	}
}
