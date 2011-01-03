<?php
/**
 * Parses Elgg manifest.xml files.
 *
 * Normalizes the values from the ElggManifestParser object.
 *
 * This requires an ElggPluginManifestParser class implementation
 * as $this->parser.
 *
 * To add new parser versions, name them ElggPluginManifestParserXX
 * where XX is the version specified in the top-level <plugin-manifest>
 * tag.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginManifest {

	/**
	 * The parser object
	 */
	protected $parser;

	/**
	 * The expected structure of a requires element
	 */
	private $depsRequiresStructPlugin = array(
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => 'ge'
	);

	/*
	 * The expected structure of elgg and elgg_release requires element
	 */
	private $depsRequiresStructElgg = array(
		'type' => '',
		'version' => '',
		'comparison' => 'ge'
	);

	/**
	 * The expected structure of a requires php_ini dependency element
	 */
	private $depsRequiresStructPhpIni = array(
		'type' => '',
		'name' => '',
		'value' => '',
		'comparison' => '='
	);

	/**
	 * The expected structure of a requires php_extension dependency element
	 */
	private $depsRequiresStructPhpExtension = array(
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => '='
	);

	/**
	 * The expected structure of a conflicts depedency element
	 */
	private $depsConflictsStruct = array(
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => '='
	);

	/**
	 * The expected structure of a provides dependency element.
	 */
	private $depsProvidesStruct = array(
		'type' => '',
		'name' => '',
		'version' => ''
	);

	/**
	 * The expected structure of a screenshot element
	 */
	private $screenshotStruct = array(
		'description' => '',
		'path' => ''
	);

	/**
	 * The API version of the manifest.
	 *
	 * @var int
	 */
	protected $apiVersion;

	/**
	 * The optional plugin id this manifest belongs to.
	 *
	 * @var string
	 */
	protected $pluginID;

	/**
	 * Load a manifest file, XmlElement or path to manifest.xml file
	 *
	 * @param mixed  $manifest  A string, XmlElement, or path of a manifest file.
	 * @param string $plugin_id Optional ID of the owning plugin. Used to
	 *                          fill in some values automatically.
	 */
	public function __construct($manifest, $plugin_id = null) {
		if ($plugin_id) {
			$this->pluginID = $plugin_id;
		}

		// see if we need to construct the xml object.
		if ($manifest instanceof XmlElement) {
			$manifest_obj = $manifest;
		} else {
			if (substr(trim($manifest), 0, 1) == '<') {
				// this is a string
				$raw_xml = $manifest;
			} elseif (is_file($manifest)) {
				// this is a file
				$raw_xml = file_get_contents($manifest);
			}

			$manifest_obj = xml_to_object($raw_xml);
		}

		if (!$manifest_obj) {
			throw new PluginException(elgg_echo('PluginException:InvalidManifest',
						array($this->getPluginID())));
		}

		// set manifest api version
		if (isset($manifest_obj->attributes['version'])) {
			$this->apiVersion = (float)$manifest_obj->attributes['version'];
		} else {
			$this->apiVersion = 1.7;
		}

		$parser_class_name = 'ElggPluginManifestParser' . str_replace('.', '', $this->apiVersion);

		// @todo currently the autoloader freaks out if a class doesn't exist.
		try {
			$class_exists = class_exists($parser_class_name);
		} catch (Exception $e) {
			$class_exists = false;
		}

		if ($class_exists) {
			$this->parser = new $parser_class_name($manifest_obj, $this);
		} else {
			throw new PluginException(elgg_echo('PluginException:NoAvailableParser',
							array($this->apiVersion, $this->getPluginID())));
		}

		if (!$this->parser->parse()) {
			throw new PluginException(elgg_echo('PluginException:ParserError',
						array($this->apiVersion, $this->getPluginID())));
		}

		return true;
	}

	/**
	 * Returns the API version in use.
	 *
	 * @return int
	 */
	public function getApiVersion() {
		return $this->apiVersion;
	}

	/**
	 * Returns the plugin ID.
	 *
	 * @return string
	 */
	public function getPluginID() {
		if ($this->pluginID) {
			return $this->pluginID;
		} else {
			return elgg_echo('unknown');
		}
	}

	/**
	 * Returns the manifest array.
	 *
	 * Used for backward compatibility.  Specific
	 * methods should be called instead.
	 *
	 * @return array
	 */
	public function getManifest() {
		return $this->parser->getManifest();
	}

	/***************************************
	 * Parsed and Normalized Manifest Data *
	 ***************************************/

	/**
	 * Returns the plugin name
	 *
	 * @return string
	 */
	public function getName() {
		$name = $this->parser->getAttribute('name');

		if (!$name && $this->pluginID) {
			$name = ucwords(str_replace('_', ' ', $this->pluginID));
		}

		return $name;
	}


	/**
	 * Return the description
	 *
	 * @return string
	 */
	public function getDescription() {
		return elgg_echo($this->parser->getAttribute('description'));
	}

	/**
	 * Return the short description
	 *
	 * @return string
	 */
	public function getBlurb() {
		$blurb = elgg_echo($this->parser->getAttribute('blurb'));

		if (!$blurb) {
			$blurb = elgg_get_excerpt($this->getDescription());
		}

		return $blurb;
	}

	/**
	 * Returns the license
	 *
	 * @return sting
	 */
	public function getLicense() {
		return $this->parser->getAttribute('license');
	}


	/**
	 * Returns the version of the plugin.
	 *
	 * @return float
	 */
	public function getVersion() {
		return $this->parser->getAttribute('version');
	}

	/**
	 * Returns the plugin author.
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->parser->getAttribute('author');
	}

	/**
	 * Return the copyright
	 *
	 * @return string
	 */
	public function getCopyright() {
		return $this->parser->getAttribute('copyright');
	}

	/**
	 * Return the website
	 *
	 * @return string
	 */
	public function getWebsite() {
		return $this->parser->getAttribute('website');
	}

	/**
	 * Return the categories listed for this plugin
	 *
	 * @return array
	 */
	public function getCategories() {
		$cats = $this->parser->getAttribute('category');

		if (!$cats) {
			$cats = array();
		}

		return $cats;
	}

	/**
	 * Return the screenshots listed.
	 *
	 * @return array
	 */
	public function getScreenshots() {
		$ss = $this->parser->getAttribute('screenshot');

		if (!$ss) {
			$ss = array();
		}

		$normalized = array();
		foreach ($ss as $s) {
			$normalized[] = $this->buildStruct($this->screenshotStruct, $s);
		}

		return $normalized;
	}

	/**
	 * Return the list of provides by this plugin.
	 *
	 * @return array
	 */
	public function getProvides() {
		// normalize for 1.7
		if ($this->getApiVersion() < 1.8) {
			$provides = array();
		} else {
			$provides = $this->parser->getAttribute('provides');
		}

		if (!$provides) {
			$provides = array();
		}

		// always provide ourself if we can
		if ($this->pluginID) {
			$provides[] = array(
				'type' => 'plugin',
				'name' => $this->getPluginID(),
				'version' => $this->getVersion()
			);
		}

		$normalized = array();
		foreach ($provides as $provide) {
			$normalized[] = $this->buildStruct($this->depsProvidesStruct, $provide);
		}

		return $normalized;
	}

	/**
	 * Returns the dependencies listed.
	 *
	 * @return array
	 */
	public function getRequires() {
		// rewrite the 1.7 style elgg_version as a real requires.
		if ($this->apiVersion < 1.8) {
			$elgg_version = $this->parser->getAttribute('elgg_version');
			if ($elgg_version) {
				$reqs = array(
					array(
						'type' => 'elgg_version',
						'version' => $elgg_version,
						'comparison' => 'ge'
					)
				);
			} else {
				$reqs = array();
			}
		} else {
			$reqs = $this->parser->getAttribute('requires');
		}

		if (!$reqs) {
			$reqs = array();
		}

		$normalized = array();
		foreach ($reqs as $req) {

			switch ($req['type']) {
				case 'elgg_version':
				case 'elgg_release':
					$struct = $this->depsRequiresStructElgg;
					break;

				case 'plugin':
					$struct = $this->depsRequiresStructPlugin;
					break;

				case 'php_extension':
					$struct = $this->depsRequiresStructPhpExtension;
					break;

				case 'php_ini':
					$struct = $this->depsRequiresStructPhpIni;

					// also normalize boolean values
					if (isset($req['value'])) {
						switch (strtolower($normalized_req['value'])) {
							case 'yes':
							case 'true':
							case 'on':
							case 1:
								$normalized_req['value'] = 1;
								break;

							case 'no':
							case 'false':
							case 'off':
							case 0:
							case '':
								$normalized_req['value'] = 0;
								break;
						}
					}

					break;
			}

			$normalized_req = $this->buildStruct($struct, $req);

			// normalize comparison operators
			switch ($normalized_req['comparison']) {
				case '<':
					$normalized_req['comparison'] = 'lt';
					break;

				case '<=':
					$normalized_req['comparison'] = 'le';
					break;

				case '>':
					$normalized_req['comparison'] = 'gt';
					break;

				case '>=':
					$normalized_req['comparison'] = 'ge';
					break;

				case '==':
				case 'eq':
					$normalized_req['comparison'] = '=';
					break;

				case '<>':
				case 'ne':
					$normalized_req['comparison'] = '!=';
					break;
			}

			$normalized[] = $normalized_req;
		}

		return $normalized;
	}

	/**
	 * Returns the conflicts listed
	 *
	 * @return array
	 */
	public function getConflicts() {
		// normalize for 1.7
		if ($this->getApiVersion() < 1.8) {
			$conflicts = array();
		} else {
			$conflicts = $this->parser->getAttribute('conflicts');
		}

		if (!$conflicts) {
			$conflicts = array();
		}

		$normalized = array();

		foreach ($conflicts as $conflict) {
			$normalized[] = $this->buildStruct($this->depsConflictsStruct, $conflict);
		}

		return $normalized;
	}

	/**
	 * Returns the functions to run upon activation
	 *
	 *  @return array
	 */
	public function getOnActivate() {
		$functions = $this->parser->getAttribute('on_activate');

		if (!$functions) {
			$functions = array();
		}

		return $functions;
	}

	/**
	 * Returns the functions to run upon deactivation
	 *
	 *  @return array
	 */
	public function getOnDeactivate() {
		$functions = $this->parser->getAttribute('on_deactivate');

		if (!$functions) {
			$functions = array();
		}

		return $functions;
	}

	/**
	 * Returns the admin interface to use.
	 *
	 *  @return string simple or advanced
	 */
	public function getAdminInterface() {
		$interface = $this->parser->getAttribute('admin_interface');

		switch ($interface) {
			case 'simple':
			case 'advanced':
				return $interface;

			default:
				return 'advanced';
		}
	}

	/**
	 * Returns the admin interface to use.
	 *
	 *  @return bool
	 */
	public function getActivateOnInstall() {
		$activate = $this->parser->getAttribute('activate_on_install');
		switch (strtolower($activate)) {
			case 'yes':
			case 'true':
			case 'on':
			case 1:
				return true;

			case 'no':
			case 'false':
			case 'off':
			case 0:
			case '':
				return false;
		}
	}

	/**
	 * Normalizes an array into the structure specified
	 *
	 * @param array $struct The struct to normalize $element to.
	 * @param array $array  The array
	 *
	 * @return array
	 */
	protected function buildStruct(array $struct, array $array) {
		$return = array();

		foreach ($struct as $index => $default) {
			$return[$index] = elgg_get_array_value($index, $array, $default);
		}

		return $return;
	}
}