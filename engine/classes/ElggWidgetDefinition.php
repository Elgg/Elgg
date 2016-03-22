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

		$definition = new \ElggWidgetDefinition($options['handler']);
		unset($options['handler']);
		
		foreach ($options as $key => $value) {
			$definition->$key = $value;
		}
		
		return $definition;
	}
}
