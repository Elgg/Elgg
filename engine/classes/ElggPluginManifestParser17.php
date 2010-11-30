<?php
/**
 * Plugin manifest.xml parser for Elgg 1.7 and lower.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 */
class ElggPluginManifestParser17 extends ElggPluginManifestParser {
	/**
	 * The valid top level attributes and defaults for a 1.7 manifest
	 */
	protected $validAttributes = array(
		'author' => null,
		'version' => null,
		'description' => null,
		'website' => null,
		'copyright' => null,
		'license' => 'GNU Public License version 2',
		'elgg_version' => null,

		// were never really used and not enforced in code.
		'requires' => null,
		'recommends' => null,
		'conflicts' => null
	);

	/**
	 * Parse a manifest object from 1.7 or earlier.
	 *
	 * @return void
	 */
	public function parse() {
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

		$this->manifest = $elements;

		return true;
	}
}