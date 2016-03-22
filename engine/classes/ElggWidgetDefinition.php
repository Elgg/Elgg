<?php

/**
 * \ElggWidgetDefinition
 *
 * Helper class for defining a widget
 *
 * @package    Elgg.Core
 * @subpackage Widgets
 *
 */
class ElggWidgetDefinition {

	/**
	 * @var string Handler of the widget
	 */
	public $handler;
	
	/**
	 * \ElggWidgetDefinition constructor
	 *
	 * @param string $handler Handler of the widget
	 */
	public function __construct($handler) {
		if (empty($handler)) {
			$msg = elgg_echo('Handler missing for \ElggWidgetDefinition');
			throw new \InvalidParameterException($msg);
		}
		
		$this->handler = $handler;
	}
	
	/**
	 * Create an \ElggWidgetDefinition from an associative array. Required key is handler.
	 *
	 * @param array $options Option array of key value pairs
	 *
	 *    handler     => STR  Widget handler/identifier (required)
	 *
	 * @throws InvalidParameterException
	 * @return \ElggWidgetDefinition
	 */
	public static function factory(array $options) {

		$handler = elgg_extract('handler', $options);
		$definition = new \ElggWidgetDefinition($handler);
		unset($options['handler']);
		
		if (!isset($options['name']) || empty($options['name'])) {
			$name = $handler;
			if (elgg_language_key_exists("widgets:{$handler}:name")) {
				$name = elgg_echo("widgets:{$handler}:name");
			} elseif (elgg_language_key_exists($handler)) {
				$name = elgg_echo($handler);
			}
			
			$options['name'] = $name;
		}
		
		if (!isset($options['description']) || empty($options['description'])) {
			if (elgg_language_key_exists("widgets:{$handler}:description")) {
				$options['description'] = elgg_echo("widgets:{$handler}:description");
			}
		}
		
		foreach ($options as $key => $value) {
			$definition->$key = $value;
		}
		
		return $definition;
	}
}
