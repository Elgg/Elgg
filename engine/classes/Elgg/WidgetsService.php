<?php
namespace Elgg;

use Elgg\Database\EntityTable\UserFetchFailureException;

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
	 * @var WidgetDefinition[]
	 */
	private $widgets = [];

	/**
	 * @see \Elgg\WidgetsService::getWidgets()
	 * @var array
	 */
	private $widgetCache = [];

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
	 * @return \ElggWidget[] An 2D array of \ElggWidget objects
	 *
	 * @see elgg_get_widgets()
	 * @access private
	 * @since 1.9.0
	 */
	public function getWidgets($owner_guid, $context) {
		$widget_cache_key = "$context-$owner_guid";

		if (isset($this->widgetCache[$widget_cache_key])) {
			return $this->widgetCache[$widget_cache_key];
		}

		$options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $owner_guid,
			'private_setting_name' => 'context',
			'private_setting_value' => $context,
			'limit' => 0,
		];
		$widgets = elgg_get_entities($options);
		if (!$widgets) {
			return [];
		}

		$sorted_widgets = [];
		foreach ($widgets as $widget) {
			if (!isset($sorted_widgets[(int) $widget->column])) {
				$sorted_widgets[(int) $widget->column] = [];
			}
			$sorted_widgets[(int) $widget->column][$widget->order] = $widget;
		}

		foreach ($sorted_widgets as $col => $widgets) {
			ksort($sorted_widgets[$col]);
		}

		$this->widgetCache[$widget_cache_key] = $sorted_widgets;

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
	 *
	 * @see elgg_create_widget()
	 * @access private
	 * @since 1.9.0
	 */
	public function createWidget($owner_guid, $handler, $context, $access_id = null) {
		if (empty($owner_guid) || empty($handler)) {
			return false;
		}

		$owner = get_entity($owner_guid);
		if (!$owner) {
			return false;
		}
		if (!$this->validateType($handler, $context, $owner)) {
			return false;
		}

		$widget = new \ElggWidget;
		$widget->owner_guid = $owner_guid;
		$widget->container_guid = $owner_guid;
		$widget->access_id = isset($access_id) ? $access_id : get_default_access();
		
		if (!$widget->save()) {
			return false;
		}

		// private settings cannot be set until \ElggWidget saved
		$widget->handler = $handler;
		$widget->context = $context;

		return $widget->getGUID();
	}

	/**
	 * Can the user edit the widget layout
	 *
	 * @param string $context   The widget context
	 * @param int    $user_guid The GUID of the user (0 for logged in user)
	 *
	 * @return bool
	 *
	 * @see elgg_can_edit_widget_layout()
	 * @access private
	 * @since 1.9.0
	 */
	public function canEditLayout($context, $user_guid = 0) {
		try {
			$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		if ($user) {
			$return = ($user->isAdmin() || (elgg_get_page_owner_guid() == $user->guid));
		} else {
			$return = false;
		}

		$params = [
			'user' => $user,
			'context' => $context,
			'page_owner' => elgg_get_page_owner_entity(),
		];
		return _elgg_services()->hooks->trigger('permissions_check', 'widget_layout', $params, $return);
	}

	/**
	 * Register a widget type
	 *
	 * @param WidgetDefinition $definition Definition of the widget
	 *
	 * @return bool
	 *
	 * @see elgg_register_widget_type()
	 * @access private
	 * @since 1.9.0
	 */
	public function registerType(WidgetDefinition $definition) {
		if (!($definition instanceof WidgetDefinition)) {
			return false;
		}
		
		$id = $definition->id;
		if (!$id) {
			return false;
		}
		
		$this->widgets[$id] = $definition;

		return true;
	}

	/**
	 * Remove a widget type
	 *
	 * @param string $id The identifier for the widget
	 *
	 * @return bool
	 *
	 * @see elgg_unregister_widget_type()
	 * @access private
	 * @since 1.9.0
	 */
	public function unregisterType($id) {
		if (isset($this->widgets[$id])) {
			unset($this->widgets[$id]);
			return true;
		}
		return false;
	}

	/**
	 * Checks if a widget type exists for a given id
	 *
	 * @param string      $id        Widget identifier
	 * @param string      $context   Optional context to check
	 * @param \ElggEntity $container Optional limit widget definitions to a container
	 *
	 * @return bool
	 *
	 * @see elgg_is_widget_type()
	 * @access private
	 * @since 1.9.0
	 */
	public function validateType($id, $context = null, \ElggEntity $container = null) {
		$types = $this->getTypes([
			'context' => $context,
			'container' => $container,
		]);
		$found = array_key_exists($id, $types);
		
		if (!$found && ($context === null)) {
			// Pre Elgg 2.2 this function returned true if a widget was registered regardless of context
			$found = array_key_exists($id, $this->widgets);
		}
		
		return $found;
	}

	/**
	 * Get all widgets
	 *
	 * @return \Elgg\WidgetDefinition[]
	 *
	 * @access private
	 * @since 1.9.0
	 */
	public function getAllTypes() {
		return $this->widgets;
	}
	
	/**
	 * Returns widget name based on id
	 *
	 * @param string      $id        Widget identifier
	 * @param string      $context   Context to check
	 * @param \ElggEntity $container Optional limit widget definitions to a container
	 *
	 * @return string|boolean
	 *
	 * @access private
	 * @since 2.2.0
	 */
	public function getNameById($id, $context = '', \ElggEntity $container = null) {
		$types = $this->getTypes([
			'context' => $context,
			'container' => $container,
		]);
		if (isset($types[$id])) {
			return $types[$id]->name;
		}
		return false;
	}

	/**
	 * @param array $params Associative array of params used to determine what to return
	 *
	 * array (
	 *     'context' => string (defaults to elgg_get_context()),
	 *     'container' => \ElggEntity (defaults to null)
	 * )
	 *
	 * @return \Elgg\WidgetDefinition[]
	 *
	 * @access private
	 * @since 1.9.0
	 */
	public function getTypes(array $params = []) {
		$context = elgg_extract('context', $params, '');
		if (!$context) {
			$context = elgg_get_context();
			$params['context'] = $context;
		}
		
		$available_widgets = _elgg_services()->hooks->trigger('handlers', 'widgets', $params, $this->widgets);
		if (!is_array($available_widgets)) {
			return [];
		}
		
		$widgets = [];
		/* @var $widget_definition \Elgg\WidgetDefinition */
		foreach ($available_widgets as $widget_definition) {
			if (!($widget_definition instanceof WidgetDefinition)) {
				continue;
			}

			if (!in_array($context, $widget_definition->context)) {
				continue;
			}
			
			if (!$widget_definition->isValid()) {
				continue;
			}
			
			$widgets[$widget_definition->id] = $widget_definition;
		}

		return $widgets;
	}
}

