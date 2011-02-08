<?php

/**
 * ElggWidget
 *
 * Stores metadata in private settings rather than as ElggMetadata
 *
 * @package    Elgg.Core
 * @subpackage Widgets
 */
class ElggWidget extends ElggObject {

	/**
	 * Set subtype to widget.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "widget";
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	public function get($name) {
		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		// No, so see if its in the private data store.
		$meta = $this->getPrivateSetting($name);
		if ($meta) {
			return $meta;
		}

		// Can't find it, so return null
		return null;
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
	 *
	 * @param string $name  Name
	 * @param string $value Value
	 *
	 * @return bool
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name == 'guid')) {
				return false;
			}

			$this->attributes[$name] = $value;
		} else {
			return $this->setPrivateSetting($name, $value);
		}

		return true;
	}

	/**
	 * Set the widget context
	 *
	 * @param string $context The widget context
	 * @return bool
	 * @since 1.8.0
	 */
	public function setContext($context) {
		return $this->setPrivateSetting('context', $context);
	}

	/**
	 * Get the widget context
	 *
	 * @return string
	 * @since 1.8.0
	 */
	public function getContext() {
		return $this->getPrivateSetting('context');
	}

	/**
	 * Get the title of the widget
	 *
	 * @return string
	 * @since 1.8.0
	 */
	public function getTitle() {
		$title = $this->title;
		if (!$title) {
			global $CONFIG;
			$title = $CONFIG->widgets->handlers[$this->handler]->name;
		}
		return $title;
	}

	/**
	 * Move the widget
	 *
	 * @param int $column The widget column
	 * @param int $rank   Zero-based rank from the top of the column
	 * @return void
	 * @since 1.8.0
	 */
	public function move($column, $rank) {
		$options = array(
			'type' => 'object',
			'subtype' => 'widget',
			'private_setting_name_value_pairs' => array(
				array('name' => 'context', 'value' => $this->getContext()),
				array('name' => 'column', 'value' => $column)
			)
		);
		$widgets = elgg_get_entities_from_private_settings($options);
		if (!$widgets) {
			$this->column = (int)$column;
			$this->order = 0;
			return;
		}

		usort($widgets, create_function('$a,$b','return (int)$a->order > (int)$b->order;'));

		if ($rank == 0) {
			// top of the column
			$this->order = $widgets[0]->order - 10;
		} elseif ($rank == count($widgets)) {
			// bottom of the column
			$this->order = end($widgets)->order + 10;
		} else {
			// reorder widgets that are below
			$this->order = $widgets[$rank]->order;
			for ($index = $rank; $index < count($widgets); $index++) {
				if ($widgets[$index]->guid != $this->guid) {
					$widgets[$index]-> order += 10;
				}
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
		$hook_params = array(
			'widget' => $this,
			'params' => $params
		);
		if (elgg_trigger_plugin_hook('widget_settings', $this->handler, $hook_params, false) == true) {
			return true;
		}

		if (is_array($params) && count($params) > 0) {
			foreach ($params as $name => $value) {
				if (is_array($value)) {
					// private settings cannot handle arrays
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
