<?php

namespace Elgg;

use Elgg\Exceptions\Database\UserFetchFailureException;

/**
 * Widgets service
 *
 * @internal
 * @since 1.9.0
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
	 */
	public function getWidgets(int $owner_guid, string $context): array {
		$widget_cache_key = "{$context}-{$owner_guid}";

		if (isset($this->widgetCache[$widget_cache_key])) {
			return $this->widgetCache[$widget_cache_key];
		}

		$widgets = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $owner_guid,
			'metadata_name' => 'context',
			'metadata_value' => $context,
			'limit' => false,
		]);
		
		if (!$widgets) {
			$this->widgetCache[$widget_cache_key] = [];
			return [];
		}

		$sorted_widgets = [];
		foreach ($widgets as $widget) {
			$widget_column = (int) $widget->column;
			
			if (!isset($sorted_widgets[$widget_column])) {
				$sorted_widgets[$widget_column] = [];
			}
			
			if (!isset($sorted_widgets[$widget_column][$widget->order])) {
				$sorted_widgets[$widget_column][$widget->order] = [];
			}
			
			$sorted_widgets[$widget_column][$widget->order][] = $widget;
		}

		foreach ($sorted_widgets as $col => $orders) {
			ksort($orders);
			$sorted_col = [];
			
			foreach ($orders as $widgets) {
				foreach ($widgets as $widget) {
					$sorted_col[] = $widget;
				}
			}
			
			$sorted_widgets[$col] = $sorted_col;
		}

		$this->widgetCache[$widget_cache_key] = $sorted_widgets;

		return $sorted_widgets;
	}

	/**
	 * Create a new widget instance
	 *
	 * @param int      $owner_guid GUID of entity that owns this widget
	 * @param string   $handler    The handler for this widget
	 * @param string   $context    The context for this widget
	 * @param null|int $access_id  If not specified, it is set to the default access level
	 *
	 * @return int|false Widget GUID or false on failure
	 *
	 * @see elgg_create_widget()
	 */
	public function createWidget(int $owner_guid, string $handler, string $context, ?int $access_id = null) {
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

		$widget = new \ElggWidget();
		$widget->owner_guid = $owner_guid;
		$widget->container_guid = $owner_guid;
		$widget->access_id = $access_id ?? elgg_get_default_access();
		
		if (!$widget->save()) {
			return false;
		}

		$widget->handler = $handler;
		$widget->context = $context;

		return $widget->guid;
	}

	/**
	 * Can the user edit the widget layout
	 *
	 * @param string $context   The widget context
	 * @param int    $user_guid The GUID of the user (0 for logged-in user)
	 *
	 * @return bool
	 *
	 * @see elgg_can_edit_widget_layout()
	 */
	public function canEditLayout(string $context, int $user_guid = 0): bool {
		try {
			$user = _elgg_services()->entityTable->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$page_owner = elgg_get_page_owner_entity();
		$default = false;
		
		if ($page_owner) {
			$default = $page_owner->canEdit($user_guid);
		} elseif ($user) {
			$default = $user->isAdmin();
		}
		
		$params = [
			'user' => $user,
			'context' => $context,
			'page_owner' => $page_owner,
		];
		return _elgg_services()->events->triggerResults('permissions_check', 'widget_layout', $params, $default);
	}

	/**
	 * Register a widget type
	 *
	 * @param WidgetDefinition $definition Definition of the widget
	 *
	 * @return void
	 *
	 * @see elgg_register_widget_type()
	 */
	public function registerType(WidgetDefinition $definition): void {
		$this->widgets[$definition->id] = $definition;
	}

	/**
	 * Remove a widget type
	 *
	 * @param string $id The identifier for the widget
	 *
	 * @return void
	 *
	 * @see elgg_unregister_widget_type()
	 */
	public function unregisterType(string $id): void {
		unset($this->widgets[$id]);
	}

	/**
	 * Checks if a widget type exists for a given id
	 *
	 * @param string           $id        Widget identifier
	 * @param null|string      $context   Optional context to check
	 * @param null|\ElggEntity $container Optional limit widget definitions to a container
	 *
	 * @return bool
	 *
	 * @see elgg_is_widget_type()
	 */
	public function validateType(string $id, ?string $context = null, ?\ElggEntity $container = null): bool {
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
	 */
	public function getAllTypes(): array {
		return $this->widgets;
	}
	
	/**
	 * Returns widget name based on id
	 *
	 * @param string           $id        Widget identifier
	 * @param string           $context   Context to check
	 * @param null|\ElggEntity $container Optional limit widget definitions to a container
	 *
	 * @return string|null
	 *
	 * @since 2.2.0
	 */
	public function getNameById(string $id, string $context = '', ?\ElggEntity $container = null): ?string {
		$types = $this->getTypes([
			'context' => $context,
			'container' => $container,
		]);
		if (isset($types[$id])) {
			return $types[$id]->name;
		}
		
		return null;
	}

	/**
	 * Returns the registered widget types.
	 *
	 * Use params to limit the result:
	 * [
	 *     'context' => string (defaults to elgg_get_context()),
	 *     'container' => \ElggEntity (defaults to null)
	 * ]
	 *
	 * @param array $params Associative array of params used to determine what to return
	 *
	 * @return \Elgg\WidgetDefinition[]
	 */
	public function getTypes(array $params = []): array {
		$context = elgg_extract('context', $params, '');
		if (!$context) {
			$context = elgg_get_context();
			$params['context'] = $context;
		}
		
		$available_widgets = _elgg_services()->events->triggerResults('handlers', 'widgets', $params, $this->widgets);
		if (!is_array($available_widgets)) {
			return [];
		}
		
		$widgets = [];
		/* @var $widget_definition \Elgg\WidgetDefinition */
		foreach ($available_widgets as $widget_definition) {
			if (!$widget_definition instanceof WidgetDefinition) {
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
