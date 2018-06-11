<?php
/**
 * Plugin manifest.xml parser for Elgg 1.8 and above.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginManifestParser18 extends \ElggPluginManifestParser {
	/**
	 * The valid top level attributes and defaults for a 1.8 manifest array.
	 *
	 * @var array
	 */
	protected $validAttributes = [
		'name',
		'author',
		'version',
		'blurb',
		'description',
		'id',
		'website',
		'copyright',
		'license',
		'requires',
		'suggests',
		'screenshot',
		'contributor',
		'category',
		'conflicts',
		'provides',
		'activate_on_install',
		'repository',
		'bugtracker',
		'donations',
	];

	/**
	 * Required attributes for a valid 1.8 manifest
	 *
	 * @var array
	 */
	protected $requiredAttributes = [
		'name', 'author', 'version', 'description', 'requires'
	];

	/**
	 * Parse a manifest object from 1.8 and later
	 *
	 * @return bool
	 *
	 * @throws PluginException
	 */
	public function parse() {
		$parsed = [];
		foreach ($this->manifestObject->children as $element) {
			switch ($element->name) {
				// single elements
				case 'blurb':
				case 'description':
				case 'name':
				case 'author':
				case 'version':
				case 'id':
				case 'website':
				case 'copyright':
				case 'license':
				case 'repository':
				case 'bugtracker':
				case 'donations':
				case 'activate_on_install':
					$parsed[$element->name] = $element->content;
					break;

				// arrays
				case 'category':
					$parsed[$element->name][] = $element->content;
					break;

				// 3d arrays
				case 'screenshot':
				case 'contributor':
				case 'provides':
				case 'conflicts':
				case 'requires':
				case 'suggests':
					if (!isset($element->children)) {
						return false;
					}

					$info = [];
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
				$msg = elgg_echo(
					'PluginException:ParserErrorMissingRequiredAttribute',
					[$attr, $this->caller->getPluginID()]
				);
					
				throw PluginException::factory('ParserErrorMissingRequiredAttribute', null, $msg);
			}
		}

		$this->manifest = $parsed;

		if (!$this->manifest) {
			return false;
		}

		return true;
	}
}
