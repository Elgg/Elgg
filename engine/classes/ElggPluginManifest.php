<?php
/**
 * Parses Elgg manifest.xml files.
 *
 * Normalizes the values from the \ElggManifestParser object.
 *
 * This requires an \ElggPluginManifestParser class implementation
 * as $this->parser.
 *
 * To add new parser versions, name them \ElggPluginManifestParserXX
 * where XX is the version specified in the top-level <plugin_manifest>
 * tag's XML namespace.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginManifest {

	/**
	 * The parser object
	 *
	 * @var \ElggPluginManifestParser18
	 */
	protected $parser;

	/**
	 * The root for plugin manifest namespaces.
	 * This is in the format http://www.elgg.org/plugin_manifest/<version>
	 */
	protected $namespace_root = 'http://www.elgg.org/plugin_manifest/';

	/**
	 * The expected structure of a plugins requires element
	 */
	private $depsStructPlugin = [
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => 'ge'
	];

	/**
	 * The expected structure of a priority element
	 */
	private $depsStructPriority = [
		'type' => '',
		'priority' => '',
		'plugin' => ''
	];

	/*
	 * The expected structure of elgg_release requires element
	 */
	private $depsStructElgg = [
		'type' => '',
		'version' => '',
		'comparison' => 'ge'
	];

	/**
	 * The expected structure of a requires php_version dependency element
	 */
	private $depsStructPhpVersion = [
		'type' => '',
		'version' => '',
		'comparison' => 'ge'
	];

	/**
	 * The expected structure of a requires php_ini dependency element
	 */
	private $depsStructPhpIni = [
		'type' => '',
		'name' => '',
		'value' => '',
		'comparison' => '='
	];

	/**
	 * The expected structure of a requires php_extension dependency element
	 */
	private $depsStructPhpExtension = [
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => '='
	];

	/**
	 * The expected structure of a conflicts depedency element
	 */
	private $depsConflictsStruct = [
		'type' => '',
		'name' => '',
		'version' => '',
		'comparison' => '='
	];

	/**
	 * The expected structure of a provides dependency element.
	 */
	private $depsProvidesStruct = [
		'type' => '',
		'name' => '',
		'version' => ''
	];

	/**
	 * The expected structure of a screenshot element
	 */
	private $screenshotStruct = [
		'description' => '',
		'path' => ''
	];

	/**
	 * The expected structure of a contributor element
	 */
	private $contributorStruct = [
		'name' => '',
		'email' => '',
		'website' => '',
		'username' => '',
		'description' => '',
	];

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
	 *
	 * @throws PluginException
	 */
	public function __construct($manifest, $plugin_id = null) {
		if ($plugin_id) {
			$this->pluginID = $plugin_id;
		}

		// see if we need to construct the xml object.
		if ($manifest instanceof \ElggXMLElement) {
			$manifest_obj = $manifest;
		} else {
			$raw_xml = '';
			if (substr(trim($manifest), 0, 1) == '<') {
				// this is a string
				$raw_xml = $manifest;
			} elseif (is_file($manifest)) {
				// this is a file
				$raw_xml = file_get_contents($manifest);
			}
			if ($raw_xml) {
				$manifest_obj = new \ElggXMLElement($raw_xml);
			} else {
				$manifest_obj = null;
			}
		}

		if (!$manifest_obj) {
			$msg = elgg_echo('PluginException:InvalidManifest', [$this->getPluginID()]);
			throw PluginException::factory('InvalidManifest', null, $msg);
		}

		// set manifest api version
		if (isset($manifest_obj->attributes['xmlns'])) {
			$namespace = $manifest_obj->attributes['xmlns'];
			$version = str_replace($this->namespace_root, '', $namespace);
		} else {
			$version = 1.7;
		}

		$this->apiVersion = $version;

		$parser_class_name = '\ElggPluginManifestParser' . str_replace('.', '', $this->apiVersion);

		// @todo currently the autoloader freaks out if a class doesn't exist.
		try {
			$class_exists = class_exists($parser_class_name);
		} catch (Exception $e) {
			$class_exists = false;
		}

		if ($class_exists) {
			$this->parser = new $parser_class_name($manifest_obj, $this);
		} else {
			$msg = elgg_echo('PluginException:NoAvailableParser', [$this->apiVersion, $this->getPluginID()]);
			throw PluginException::factory('NoAvailableParser', null, $msg);
		}

		if (!$this->parser->parse()) {
			$msg = elgg_echo('PluginException:ParserError', [$this->apiVersion, $this->getPluginID()]);
			throw PluginException::factory('ParseError', null, $msg);
		}
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
	 * Return the plugin ID required by the author. If getPluginID() does
	 * not match this, the plugin should not be started.
	 *
	 * @return string empty string if not empty/not defined
	 */
	public function getID() {
		return trim((string) $this->parser->getAttribute('id'));
	}


	/**
	 * Return the description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->parser->getAttribute('description');
	}

	/**
	 * Return the short description
	 *
	 * @return string
	 */
	public function getBlurb() {
		$blurb = $this->parser->getAttribute('blurb');

		if (!$blurb) {
			$blurb = elgg_get_excerpt($this->getDescription());
		}

		return $blurb;
	}

	/**
	 * Returns the license
	 *
	 * @return string
	 */
	public function getLicense() {
		// license vs licence.  Use license.
		$en_us = $this->parser->getAttribute('license');
		if ($en_us) {
			return $en_us;
		} else {
			return $this->parser->getAttribute('licence');
		}
	}

	/**
	 * Returns the repository url
	 *
	 * @return string
	 */
	public function getRepositoryURL() {
		return $this->parser->getAttribute('repository');
	}

	/**
	 * Returns the bug tracker page
	 *
	 * @return string
	 */
	public function getBugTrackerURL() {
		return $this->parser->getAttribute('bugtracker');
	}

	/**
	 * Returns the donations page
	 *
	 * @return string
	 */
	public function getDonationsPageURL() {
		return $this->parser->getAttribute('donations');
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
		$bundled_plugins = [
			'activity',
			'blog',
			'bookmarks',
			'ckeditor',
			'custom_index',
			'dashboard',
			'developers',
			'diagnostics',
			'discussions',
			'embed',
			'externalpages',
			'file',
			'friends',
			'friends_collections',
			'garbagecollector',
			'groups',
			'invitefriends',
			'likes',
			'login_as',
			'members',
			'messageboard',
			'messages',
			'notifications',
			'pages',
			'profile',
			'reportedcontent',
			'search',
			'site_notifications',
			'system_log',
			'tagcloud',
			'thewire',
			'uservalidationbyemail',
			'web_services',
		];

		$cats = $this->parser->getAttribute('category');

		if (!$cats) {
			$cats = [];
		}

		if (in_array('bundled', $cats) && !in_array($this->getPluginID(), $bundled_plugins)) {
			unset($cats[array_search('bundled', $cats)]);
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
			$ss = [];
		}

		$normalized = [];
		foreach ($ss as $s) {
			$normalized[] = $this->buildStruct($this->screenshotStruct, $s);
		}

		return $normalized;
	}

	/**
	 * Return the contributors listed.
	 *
	 * @return array
	 */
	public function getContributors() {
		$ss = $this->parser->getAttribute('contributor');

		if (!$ss) {
			$ss = [];
		}

		$normalized = [];
		foreach ($ss as $s) {
			$normalized[] = $this->buildStruct($this->contributorStruct, $s);
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
			$provides = [];
		} else {
			$provides = $this->parser->getAttribute('provides');
		}

		if (!$provides) {
			$provides = [];
		}

		// always provide ourself if we can
		if ($this->pluginID) {
			$provides[] = [
				'type' => 'plugin',
				'name' => $this->getPluginID(),
				'version' => $this->getVersion()
			];
		}

		$normalized = [];
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
		$reqs = $this->parser->getAttribute('requires');

		if (!$reqs) {
			$reqs = [];
		}

		$normalized = [];
		foreach ($reqs as $req) {
			$normalized[] = $this->normalizeDep($req);
		}

		return $normalized;
	}

	/**
	 * Returns the suggests elements.
	 *
	 * @return array
	 */
	public function getSuggests() {
		$suggests = $this->parser->getAttribute('suggests');

		if (!$suggests) {
			$suggests = [];
		}

		$normalized = [];
		foreach ($suggests as $suggest) {
			$normalized[] = $this->normalizeDep($suggest);
		}

		return $normalized;
	}

	/**
	 * Normalizes a dependency array using the defined structs.
	 * Can be used with either requires or suggests.
	 *
	 * @param array $dep A dependency array.
	 * @return array The normalized deps array.
	 */
	private function normalizeDep($dep) {
		
		$struct = [];
		
		switch ($dep['type']) {
			case 'elgg_release':
				$struct = $this->depsStructElgg;
				break;

			case 'plugin':
				$struct = $this->depsStructPlugin;
				break;

			case 'priority':
				$struct = $this->depsStructPriority;
				break;

			case 'php_version':
				$struct = $this->depsStructPhpVersion;
				break;

			case 'php_extension':
				$struct = $this->depsStructPhpExtension;
				break;

			case 'php_ini':
				$struct = $this->depsStructPhpIni;

				// also normalize boolean values
				if (isset($dep['value'])) {
					switch (strtolower($dep['value'])) {
						case 'yes':
						case 'true':
						case 'on':
						case 1:
							$dep['value'] = 1;
							break;

						case 'no':
						case 'false':
						case 'off':
						case 0:
						case '':
							$dep['value'] = 0;
							break;
					}
				}
				break;
			default:
				// unrecognized so we just return the raw dependency
				return $dep;
		}
		
		$normalized_dep = $this->buildStruct($struct, $dep);

		// normalize comparison operators
		if (isset($normalized_dep['comparison'])) {
			switch ($normalized_dep['comparison']) {
				case '<':
					$normalized_dep['comparison'] = 'lt';
					break;

				case '<=':
					$normalized_dep['comparison'] = 'le';
					break;

				case '>':
					$normalized_dep['comparison'] = 'gt';
					break;

				case '>=':
					$normalized_dep['comparison'] = 'ge';
					break;

				case '==':
				case 'eq':
					$normalized_dep['comparison'] = '=';
					break;

				case '<>':
				case 'ne':
					$normalized_dep['comparison'] = '!=';
					break;
			}
		}

		return $normalized_dep;
	}

	/**
	 * Returns the conflicts listed
	 *
	 * @return array
	 */
	public function getConflicts() {
		// normalize for 1.7
		if ($this->getApiVersion() < 1.8) {
			$conflicts = [];
		} else {
			$conflicts = $this->parser->getAttribute('conflicts');
		}

		if (!$conflicts) {
			$conflicts = [];
		}

		$normalized = [];

		foreach ($conflicts as $conflict) {
			$normalized[] = $this->buildStruct($this->depsConflictsStruct, $conflict);
		}

		return $normalized;
	}

	/**
	 * Should this plugin be activated when Elgg is installed
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
		$return = [];

		foreach ($struct as $index => $default) {
			$return[$index] = elgg_extract($index, $array, $default);
		}

		return $return;
	}

	/**
	 * Returns a category's friendly name. This can be localized by
	 * defining the string 'admin:plugins:category:<category>'. If no
	 * localization is found, returns the category with _ and - converted to ' '
	 * and then ucwords()'d.
	 *
	 * @param string $category The category as defined in the manifest.
	 * @return string A human-readable category
	 */
	static public function getFriendlyCategory($category) {
		$cat_raw_string = "admin:plugins:category:$category";
		if (_elgg_services()->translator->languageKeyExists($cat_raw_string)) {
			return elgg_echo($cat_raw_string);
		}
		
		$category = str_replace(['-', '_'], ' ', $category);
		return ucwords($category);
	}
}
