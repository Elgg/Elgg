<?php
/**
 * Plugin manifest.xml parser for Elgg 1.7 and lower.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginManifestParser17 extends ElggPluginManifestParser {
	/**
	 * The valid top level attributes and defaults for a 1.7 manifest
	 */
	protected $validAttributes = array(
		'author', 'version', 'description', 'website',
		'copyright', 'license', 'licence', 'elgg_version',

		// were never really used and not enforced in code.
		'requires', 'recommends', 'conflicts',

		// not a 1.7 field, but we need it
		'name',
	);

	/**
	 * Parse a manifest object from 1.7 or earlier.
	 *
	 * @return void
	 */
	public function parse() {
		if (!isset($this->manifestObject->children)) {
			return false;
		}

		$elements = array();

		foreach ($this->manifestObject->children as $element) {
			$key = $element->attributes['key'];
			$value = $element->attributes['value'];

			// create arrays if multiple fields are set
			if (array_key_exists($key, $elements)) {
				if (!is_array($elements[$key])) {
					$orig = $elements[$key];
					$elements[$key] = array($orig);
				}

				$elements[$key][] = $value;
			} else {
				$elements[$key] = $value;
			}
		}

		if ($elements && !array_key_exists('name', $elements)) {
			$elements['name'] = $this->caller->getName();
		}

		$this->manifest = $elements;

		if (!$this->manifest) {
			return false;
		}

		return true;
	}

	/**
	 * Return an attribute in the manifest.
	 *
	 * Overrides ElggPluginManifestParser::getAttribute() because before 1.8
	 * there were no rules...weeeeeeeee!
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute($name) {
		if (isset($this->manifest[$name])) {
			return $this->manifest[$name];
		}

		return false;
	}
}
