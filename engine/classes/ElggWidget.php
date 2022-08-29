<?php
/**
 * \ElggWidget
 *
 * Stores metadata in private settings rather than as \ElggMetadata
 *
 * @property-read string $handler internal, do not use
 * @property-read string $column  internal, do not use
 * @property-read string $order   internal, do not use
 * @property-read string $context internal, do not use
 */
class ElggWidget extends \ElggObject {

	/**
	 * Set subtype to widget.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'widget';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDisplayName() {
		$result = parent::getDisplayName();
		if ($result) {
			return $result;
		}
		
		$container = $this->getContainerEntity() ? : null;
		return _elgg_services()->widgets->getNameById($this->handler, (string) $this->context, $container) ?: (string) $this->handler;
	}

	/**
	 * Move the widget
	 *
	 * @param int $column The widget column
	 * @param int $rank   Zero-based rank from the top of the column
	 *
	 * @return void
	 * @since 1.8.0
	 */
	public function move($column, $rank) {
		/* @var $widgets \ElggWidget[] */
		$widgets = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'widget',
			'container_guid' => $this->container_guid,
			'limit' => false,
			'metadata_name_value_pairs' => [
				['name' => 'context', 'value' => (string) $this->context],
				['name' => 'column', 'value' => $column],
			],
		]);
		
		if (empty($widgets)) {
			$this->column = (int) $column;
			$this->order = 0;
			return;
		}

		usort($widgets, function($a, $b) {
			return ((int) $a->order > (int) $b->order) ? 1 : -1;
		});

		// remove widgets from inactive plugins
		$widget_types = elgg_get_widget_types([
			'context' => $this->context,
			'container' => $this->getContainerEntity(),
		]);
		
		$inactive_widgets = [];
		foreach ($widgets as $index => $widget) {
			if (!array_key_exists($widget->handler, $widget_types)) {
				$inactive_widgets[] = $widget;
				unset($widgets[$index]);
			}
		}

		$bottom_rank = count($widgets);
		if ($column == $this->column) {
			$bottom_rank--;
		}
		
		if ($rank == 0) {
			// top of the column
			$this->order = reset($widgets)->order - 10;
		} elseif ($rank == $bottom_rank) {
			// bottom of the column of active widgets
			$this->order = end($widgets)->order + 10;
		} else {
			// reorder widgets

			// remove the widget that's being moved from the array
			foreach ($widgets as $index => $widget) {
				if ($widget->guid == $this->guid) {
					unset($widgets[$index]);
				}
			}

			// split the array in two and recombine with the moved widget in middle
			$before = array_slice($widgets, 0, $rank);
			array_push($before, $this);
			$after = array_slice($widgets, $rank);
			$widgets = array_merge($before, $after);
			ksort($widgets);
			$order = 0;
			foreach ($widgets as $widget) {
				$widget->order = $order;
				$order += 10;
			}
		}

		// put inactive widgets at the bottom
		if ($inactive_widgets) {
			$bottom = 0;
			foreach ($widgets as $widget) {
				if ($widget->order > $bottom) {
					$bottom = $widget->order;
				}
			}
			$bottom += 10;
			foreach ($inactive_widgets as $widget) {
				$widget->order = $bottom;
				$bottom += 10;
			}
		}

		$this->column = $column;
	}

	/**
	 * Saves the widget's settings
	 *
	 * Plugins can override the save mechanism using the plugin hook:
	 * 'widget_settings', <widget handler identifier>. The widget and
	 * the parameters are passed. The plugin hook handler should return
	 * true to indicate that it has successfully saved the settings.
	 *
	 * @warning The values in the parameter array cannot be arrays
	 *
	 * @param array $params An array of name => value parameters
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public function saveSettings($params) {
		if (!$this->canEdit()) {
			return false;
		}

		// plugin hook handlers should return true to indicate the settings have
		// been saved so that default code does not run
		$hook_params = [
			'widget' => $this,
			'params' => $params
		];
		if (_elgg_services()->hooks->trigger('widget_settings', $this->handler, $hook_params, false) === true) {
			return true;
		}

		if (is_array($params) && count($params) > 0) {
			foreach ($params as $name => $value) {
				if (is_array($value)) {
					// @todo what do we want with arrays? metadata supports it
					return false;
				} else {
					$this->$name = $value;
				}
			}
			
			$this->save();
		}

		return true;
	}
}
