<?php
/**
 * Plugin manifest.xml parser for Elgg 1.8 and above.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginManifestParser18 extends ElggPluginManifestParser {
	/**
	 * The valid top level attributes and defaults for a 1.8 manifest array.
	 *
	 * @var array
	 */
	protected $validAttributes = array(
		'name', 'author', 'version', 'blurb', 'description',
		'website', 'copyright', 'license', 'requires', 'screenshot',
		'category', 'conflicts', 'provides', 'admin'
	);

	/**
	 * Required attributes for a valid 1.8 manifest
	 *
	 * @var array
	 */
	protected $requiredAttributes = array(
		'name', 'author', 'version', 'description', 'requires'
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
				case 'blurb':
				case 'description':
				case 'name':
				case 'author':
				case 'version':
				case 'website':
				case 'copyright':
				case 'license':
					$parsed[$element->name] = $element->content;
					break;

				// arrays
				case 'category':
					$parsed['category'][] = $element->content;
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

				case 'screenshot':
				case 'provides':
				case 'conflicts':
				case 'requires':
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

		if (!$this->manifest) {
			return false;
		}

		return true;
	}
}