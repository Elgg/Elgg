<?php
namespace Elgg;

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
	 * WidgetDefinition constructor
	 *
	 * @param string $id Identifier of the widget
	 */
	public function __construct($id) {
		if (empty($id)) {
			throw new \InvalidParameterException('Id missing for WidgetDefinition');
		}
		
		$this->id = $id;
	}
	
	/**
	 * Create an WidgetDefinition from an associative array. Required key is id.
	 *
	 * @param array $options Option array of key value pairs
	 *
	 *    id => STR Widget identifier (required)
	 *    name => STR Name of the widget
	 *    description => STR Description of the widget
	 *    context => ARRAY contexts in which the widget is available
	 *    multiple => BOOL can the widget be added multiple times
	 *
	 * @throws InvalidParameterException
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
		
		$definition->context = (array) elgg_extract('context', $options, ['all']);
		$definition->multiple = (bool) elgg_extract('multiple', $options, false);
		
		return $definition;
	}
	
	/**
	 * Magic getter to return the deprecated attribute 'handler'
	 *
	 * @param string $name attribute to get
	 *
	 * @return mixed
	 */
	public function __get($name) {
		if ($name === 'handler') {
			// before Elgg 2.2 the widget definitions had the handler attribute as the id
			return $this->id;
		}
		
		return $this->$name;
	}
}
