<?php
/**
 * Parses Elgg manifest.xml files.
 *
 * This requires an ElggPluginManifestParser class implementation
 * as $this->parser.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 */
class ElggPluginManifest {

	/**
	 * The parser object
	 */
	protected $parser;

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
			} elseif (is_readable($manifest)) {
				// this is a file
				$raw_xml = file_get_contents($manifest);
			}

			$manifest_obj = xml_to_object($raw_xml);
		}

		if (!$manifest_obj) {
			throw new PluginException(elgg_echo('PluginException:InvalidManifest', array($this->getPluginID())));
		}

		// set manifest api version
		if (isset($manifest_obj->attributes['version'])) {
			$this->apiVersion = (float)$manifest_obj->attributes['version'];
		} else {
			$this->apiVersion = 1.7;
		}

		switch ($this->apiVersion) {
			case 1.8:
				$this->parser = new ElggPluginManifestParser18($manifest_obj, $this);
				break;

			case 1.7:
				$this->parser = new ElggPluginManifestParser17($manifest_obj, $this);
				break;

			default:
				throw new PluginException(elgg_echo('PluginException:NoAvailableParser',
							array($this->apiVersion, $this->getPluginID())));
				break;
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

	/**
	 * Returns the dependencies listed.
	 *
	 * @return array
	 */
	public function getDepends() {
		$deps = $this->parser->getAttribute('depends');

		if (!is_array($deps)) {
			$deps = array();
		}

		return $deps;
	}

	/**
	 * Returns the conflicts listed
	 *
	 * @return array
	 */
	public function getConflicts() {
		$conflicts = $this->parser->getAttribute('conflicts');

		if (!is_array($conflicts)) {
			$conflicts = array();
		}

		return $conflicts;
	}

	/**
	 * Returns the plugin name
	 *
	 * @return string
	 */
	public function getName() {
		$name = $this->parser->getAttribute('name');

		if (!$name && $this->pluginID) {
			$name = ucwords(str_replace('_', ' ', $pluginID));
		}

		return $name;
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
		$cats = $this->parser->getAttribute('categories');

		if (!is_array($cats)) {
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
		$ss = $this->parser->getAttribute('screenshots');

		if (!is_array($ss)) {
			$ss = array();
		}

		return $ss;
	}

	/**
	 * Return the list of provides by this plugin.
	 *
	 * @return array
	 */
	public function getProvides() {
		$provides = $this->parser->getAttribute('provides');

		// always provide ourself if we can
		if ($this->pluginID) {
			$provides[] = array('name' => $this->getPluginID(), 'version' => $this->getVersion);
		}

		return $provides;
	}
}