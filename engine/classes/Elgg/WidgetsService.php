<?php
namespace Elgg;

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
class WidgetsService {

	/**
	 * @var \stdClass
	 */
	private $widgets;

	/**
	 * @see \Elgg\WidgetsService::getWidgets()
	 * @var array
	 */
	private $widgetCache = array();

	/**
	 * @see elgg_get_widgets
	 * @access private
	 * @since 1.9.0
	 */
	public function getWidgets($owner_guid, $context) {
		$widget_cache_key = "$context-$owner_guid";

		if (isset($this->widgetCache[$widget_cache_key])) {
			return $this->widgetCache[$widget_cache_key];
		}

		$options = array(
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $owner_guid,
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

		$this->widgetCache[$widget_cache_key] = $sorted_widgets;

		return $sorted_widgets;
	}

	/**
	 * @see elgg_create_widget
	 * @access private
	 * @since 1.9.0
	 */
	public function createWidget($owner_guid, $handler, $context, $access_id = null) {
		if (empty($owner_guid) || empty($handler) || !$this->validateType($handler)) {
			return false;
		}

		$owner = get_entity($owner_guid);
		if (!$owner) {
			return false;
		}

		$widget = new \ElggWidget;
		$widget->owner_guid = $owner_guid;
		$widget->container_guid = $owner_guid; // @todo - will this work for group widgets?
		if (isset($access_id)) {
			$widget->access_id = $access_id;
		} else {
			$widget->access_id = get_default_access();
		}

		if (!$widget->save()) {
			return false;
		}

		// private settings cannot be set until \ElggWidget saved
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
			$user = _elgg_services()->session->getLoggedInUser();
		}

		$return = false;
		if (_elgg_services()->session->isAdminLoggedIn()) {
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
		return _elgg_services()->hooks->trigger('permissions_check', 'widget_layout', $params, $return);
	}

	/**
	 * @see elgg_register_widget_type
	 * @access private
	 * @since 1.9.0
	 */
	public function registerType($handler, $name, $description, array $context = array('all'), $multiple = false) {
		if (!$handler || !$name) {
			return false;
		}

		if (!isset($this->widgets)) {
			$this->widgets = new \stdClass;
		}
		if (!isset($this->widgets->handlers)) {
			$this->widgets->handlers = array();
		}

		$handlerobj = new \stdClass;
		$handlerobj->name = $name;
		$handlerobj->description = $description;
		$handlerobj->context = $context;
		$handlerobj->multiple = $multiple;

		$this->widgets->handlers[$handler] = $handlerobj;

		return true;
	}

	/**
	 * @param string $handler
	 * @return bool
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
}

