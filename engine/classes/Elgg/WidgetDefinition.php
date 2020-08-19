<?php

namespace Elgg;

use Elgg\Exceptions\InvalidParameterException;

/**
 * WidgetDefinition
 *
 * Helper class for defining a widget
 *
 */
class WidgetDefinition {

	/**
	 * @var string Identifier of the widget
	 */
	public $id;

	/**
	 * @var string Readable name of the widget
	 */
	public $name;

	/**
	 * @var string Description of the widget
	 */
	public $description;

	/**
	 * @var array In which contexts is the widget available
	 */
	public $context;

	/**
	 * @var bool Can the widget be added multiple times
	 */
	public $multiple;

	/**
	 * @var array A list of optional required plugins that need to be activated for this definition to be available
	 */
	public $required_plugin;
	
	/**
	 * WidgetDefinition constructor
	 *
	 * @param string $id Identifier of the widget
	 * @throws InvalidParameterException
	 */
	public function __construct($id) {
		if (empty($id)) {
			throw new InvalidParameterException('Id missing for WidgetDefinition');
		}
		
		$this->id = $id;
	}
	
	/**
	 * Create an WidgetDefinition from an associative array. Required key is id.
	 *
	 * @param array $options Option array of key value pairs
	 *                       - id => STR Widget identifier (required)
	 *                       - name => STR Name of the widget
	 *                       - description => STR Description of the widget
	 *                       - context => ARRAY contexts in which the widget is available
	 *                       - multiple => BOOL can the widget be added multiple times
	 *
	 * @return \Elgg\WidgetDefinition
	 */
	public static function factory(array $options) {

		$id = elgg_extract('id', $options);
		$definition = new WidgetDefinition($id);
		
		$name = elgg_extract('name', $options);
		if (empty($name)) {
			if (elgg_language_key_exists("widgets:{$id}:name")) {
				$definition->name = elgg_echo("widgets:{$id}:name");
			} elseif (elgg_language_key_exists($id)) {
				$definition->name = elgg_echo($id);
			} else {
				$definition->name = $id;
			}
		} else {
			$definition->name = $name;
		}
		
		$description = elgg_extract('description', $options);
		if (empty($description)) {
			if (elgg_language_key_exists("widgets:{$id}:description")) {
				$definition->description = elgg_echo("widgets:{$id}:description");
			}
		} else {
			$definition->description = $description;
		}
		
		$context = (array) elgg_extract('context', $options, ['all']);
		if (in_array('all', $context)) {
			$context[] = 'profile';
			$context[] = 'dashboard';
			
			_elgg_services()->logger->warning("The widget '{$id}' need to be registered for explicit contexts");
			$pos = array_search('all', $context);
			unset($context[$pos]);
			
			$context = array_unique($context);
		}
		$definition->context = $context;
		
		$definition->multiple = (bool) elgg_extract('multiple', $options, false);
		
		$definition->required_plugin = (array) elgg_extract('required_plugin', $options, []);
		
		return $definition;
	}
	
	/**
	 * Checks if the widget definition meets all requirements
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->checkRequiredActivePlugins();
	}
	
	/**
	 * Checks if the required plugins are active. If none set this function will return true.
	 *
	 * @return boolean
	 */
	protected function checkRequiredActivePlugins() {
		if (empty($this->required_plugin)) {
			return true;
		}
		
		foreach ($this->required_plugin as $plugin) {
			if (!elgg_is_active_plugin($plugin)) {
				return false;
			}
		}
		
		return true;
	}
}
