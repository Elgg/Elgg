<?php
/**
 * Plugin manifest.xml parser for Elgg 1.8 and above.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 */
class ElggPluginManifestParser18 extends ElggPluginManifestParser {
	/**
	 * The valid top level attributes and defaults for a 1.8 manifest array.
	 *
	 * @var array
	 */
	protected $validAttributes = array(
		'name' => null,
		'author' => null,
		'version' => null,
		'blurb' => null,
		'description' => null,
		'website' => null,
		'copyright' => null,
		'license' => 'GNU Public License version 2',
		'depends' => array(),
		'screenshots' => array(),
		'conflicts' => array(),
		'provides' => array(),
		'admin' => array(
			'on_enable' => null,
			'on_disable' => null,
			'interface_type' => 'advanced'
		)
	);

	/**
	 * Required attributes for a valid 1.8 manifest
	 *
	 * @var array
	 */
	protected $requiredAttributes = array(
		'name', 'author', 'version', 'description', 'depends'
	);

	/**
	 * Parse a manifest object from 1.8 and later
	 *
	 * @return void
	 */
	public function parse() {
		$parsed = array();
		foreach ($this->manifestObject->children as $element) {
			switch ($element->name) {
				// single elements
				// translatable
				case 'blurb':
				case 'description':
					$element->content = elgg_echo($element->content);

				case 'name':
				case 'author':
				case 'version':
				case 'website':
				case 'copyright':
				case 'license':
					$parsed[$element->name] = $element->content;
					break;

				// arrays
				case 'screenshot':
					if (isset($element->attributes['description'])) {
						$description = elgg_echo($element->attributes['description']);
					}
					$parsed['screenshots'][] = array(
						'description' => $description,
						'path' => $element->content
					);
					break;

				case 'admin':
					$parsed['admin'] = array();
					if (!isset($element->children)) {
						return false;
					}

					foreach ($element->children as $child_element) {
						$parsed['admin'][$child_element->name] = $child_element->content;
					}

					break;

				case 'provides':
				case 'conflicts':
				case 'depends':
					if (!isset($element->children)) {
						return false;
					}

					$info = array();
					foreach ($element->children as $child_element) {
						$info[$child_element->name] = $child_element->content;
					}

					$parsed[$element->name][] = $info;
					break;
			}
		}

		// check we have all the required fields
		foreach ($this->requiredAttributes as $attr) {
			if (!array_key_exists($attr, $parsed)) {
				throw new PluginException(elgg_echo('PluginException:ParserErrorMissingRequiredAttribute',
							array($attr, $this->caller->getPluginID())));
			}
		}

		$this->manifest = $parsed;

		return true;
	}
}