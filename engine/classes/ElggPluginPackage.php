<?php
/**
 * Manages plugin packages under mod.
 *
 * @todo This should eventually be merged into ElggPlugin.
 * Currently ElggPlugin objects are only used to get and save
 * plugin settings and user settings, so not every plugin
 * has an ElggPlugin object.  It's not implemented in ElggPlugin
 * right now because of conflicts with at least the constructor,
 * enable(), disable(), and private settings.
 *
 * Around 1.9 or so we should each plugin over to using
 * ElggPlugin and merge ElggPluginPackage and ElggPlugin.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginPackage {

	/**
	 * The required files in the package
	 *
	 * @var array
	 */
	private $_requiredFiles = array(
		'start.php', 'manifest.xml'
	);

	/**
	 * Valid types for provides.
	 *
	 * @var array
	 */
	private $_providesSupportedTypes = array(
		'plugin', 'php_extension'
	);

	/**
	 * The type of requires/conflicts supported
	 *
	 * @var array
	 */
	private $_depsSupportedTypes = array(
		'elgg_version', 'elgg_release', 'php_extension', 'php_ini', 'plugin'
	);

	/**
	 * An invalid plugin error.
	 */
	private $_invalidPluginError = '';

	/**
	 * Any dependencies messages
	 */
	private $_depsMsgs = array();

	/**
	 * The plugin's manifest object
	 *
	 * @var ElggPluginManifest
	 */
	protected $manifest;

	/**
	 * The plugin's full path
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Is the plugin valid?
	 *
	 * @var mixed Bool after validation check, null before.
	 */
	protected $valid = null;

	/**
	 * The plugin ID (dir name)
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Load a plugin package from mod/$id or by full path.
	 *
	 * @param string $plugin   The ID (directory name) or full path of the plugin.
	 * @param bool   $validate Automatically run isValid()?
	 *
	 * @return true
	 * @throws PluginException
	 */
	public function __construct($plugin, $validate = true) {
		if (substr($plugin, 0, 1) == '/') {
			// this is a path
			$plugin = sanitise_filepath($plugin);

			if (!is_dir($plugin)) {
				throw new PluginException(elgg_echo('PluginException:InvalidPath', array($plugin)));
			}

			// the id is the last element of the array
			$path_array = explode('/', trim($plugin, '/'));
			$this->id = array_pop($path_array);
			$this->path = $plugin;
		} else {
			// this is a plugin name

			// strict plugin names
			if (preg_match('/[^a-z0-9\.\-_]/i', $id)) {
				throw new PluginException(elgg_echo('PluginException:InvalidID', array($plugin)));
			}

			$this->id = $plugin;
			$this->path = get_config('pluginspath') . "$plugin/";
		}

		if ($validate && !$this->isValid()) {
			if ($this->_invalidPluginError) {
				throw new PluginException(elgg_echo('PluginException:InvalidPlugin:Details',
							array($plugin, $this->_invalidPluginError)));
			} else {
				throw new PluginException(elgg_echo('PluginException:InvalidPlugin', array($plugin)));
			}
		}

		return true;
	}

	/********************************
	 * Validation and sanity checks *
	 ********************************/

	/**
	 * Checks if this is a valid Elgg plugin.
	 *
	 * Checks for requires files as defined at the start of this
	 * class.  Will check require manifest fields via ElggPluginManifest
	 * for Elgg 1.8 plugins.
	 *
	 * @note This doesn't check dependencies or conflicts.
	 * Use {@link ElggPluginPackage::canActivate()} or
	 * {@link ElggPluginPackage::checkDependencies()} for that.
	 *
	 * @return bool
	 */
	public function isValid() {
		if (isset($this->valid)) {
			return $this->valid;
		}

		$valid = true;

		// check required files.
		$have_req_files = true;
		foreach ($this->_requiredFiles as $file) {
			if (!is_readable($this->path . $file)) {
				$have_req_files = false;
				$this->_invalidPluginError =
					elgg_echo('ElggPluginPackage:InvalidPlugin:MissingFile', array($file));
				break;
			}
		}

		// check required files
		if (!$have_req_files) {
			$valid = false;
		}

		// check for valid manifest.
		if (!$this->_loadManifest()) {
			$valid = false;
		}

		// can't require or conflict with yourself or something you provide.
		// make sure provides are all valid.
		if (!$this->_isSaneDeps()) {
			$valid = false;
		}

		$this->valid = $valid;

		return $valid;
	}

	/**
	 * Check the plugin doesn't require or conflict with itself
	 * or something provides.  Also check that it only list
	 * valid provides.  Deps are checked in checkDependencies()
	 *
	 * @note Plugins always provide themselves.
	 *
	 * @todo Don't let them require and conflict the same thing
	 *
	 * @return bool
	 */
	private function _isSaneDeps() {
		$conflicts = $this->getManifest()->getConflicts();
		$requires = $this->getManifest()->getRequires();
		$provides = $this->getManifest()->getProvides();

		foreach ($provides as $provide) {
			// only valid provide types
			if (!in_array($provide['type'], $this->_providesSupportedTypes)) {
				$this->_invalidPluginError =
					elgg_echo('ElggPluginPackage:InvalidPlugin:InvalidProvides', array($provide['type']));
				return false;
			}

			// doesn't conflict or require any of its provides
			$name = $provide['name'];
			foreach (array('conflicts', 'requires') as $dep_type) {
				foreach (${$dep_type} as $dep) {
					if (!in_array($dep['type'], $this->_depsSupportedTypes)) {
						$this->_invalidPluginError =
							elgg_echo('ElggPluginPackage:InvalidPlugin:InvalidDependency', array($dep['type']));
						return false;
					}

					// make sure nothing is providing something it conflicts or requires.
					if ($dep['name'] == $name) {
						$version_compare = version_compare($provide['version'], $dep['version'], $dep['comparison']);

						if ($version_compare) {
							$this->_invalidPluginError =
								elgg_echo('ElggPluginPackage:InvalidPlugin:CircularDep',
									array($dep['type'], $dep['name'], $this->id));

							return false;
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * Checks if this plugin can be activated on the current
	 * Elgg installation.
	 *
	 * @return bool
	 */
	public function canActivate() {
		return $this->checkDependencies();
	}


	/************
	 * Manifest *
	 ************/

	/**
	 * Returns a parsed manifest file.
	 *
	 * @return ElggPluginManifest
	 */
	public function getManifest() {
		if (!$this->manifest) {
			$this->_loadManifest();
		}

		return $this->manifest;
	}

	/**
	 * Loads the manifest into this->manifest as an
	 * ElggPluginManifest object.
	 *
	 * @return bool
	 */
	private function _loadManifest() {
		$file = $this->path . 'manifest.xml';
		$this->manifest = new ElggPluginManifest($file, $this->id);

		if ($this->manifest) {
			return true;
		}

		return false;
	}


	/***********************
	 * Dependencies system *
	 ***********************/

	/**
	 * Returns if the Elgg system meets the plugin's dependency
	 * requirements.  This includes both requires and conflicts.
	 *
	 * Full reports can be requested.  The results are returned
	 * as an array of arrays in the form array(
	 * 	'type' => requires|conflicts,
	 * 	'dep' => array( dependency array ),
	 * 	'status' => bool if depedency is met,
	 * 	'comment' => optional comment to display to the user.
	 * )
	 *
	 * @param bool $full_report Return a full report.
	 * @return bool|array
	 */
	public function checkDependencies($full_report = false) {
		$requires = $this->getManifest()->getRequires();
		$conflicts = $this->getManifest()->getConflicts();
		$enabled_plugins = get_installed_plugins('enabled');
		$report = array();

		foreach (array('requires', 'conflicts') as $dep_type) {
			$inverse = ($dep_type == 'conflicts') ? true : false;

			foreach (${$dep_type} as $dep) {
				switch ($dep['type']) {
					case 'elgg_version':
						$result = $this->_checkDepElgg($dep, get_version());
						break;

					case 'elgg_release':
						$result = $this->_checkDepElgg($dep, get_version(true));
						break;

					case 'plugin':
						$result = $this->_checkDepPlugin($dep, $enabled_plugins, $inverse);
						break;

					case 'php_extension':
						$result = $this->_checkDepPhpExtension($dep);
						break;

					case 'php_ini':
						$result = $this->_checkDepPhpIni($dep);
						break;
				}

				// unless we're doing a full report, break as soon as we fail.
				if (!$full_report && !$result) {
					return $result;
				} else {
					// build report element and comment
					if ($dep_type == 'requires') {
						$comment = '';
					} elseif ($dep_type == 'conflicts') {
						$comment = '';
					}

					$report[] = array(
						'type' => $dep_type,
						'dep' => $dep,
						'status' => $result,
						'comment' => $comment
					);
				}
			}
		}

		if ($full_report) {
			return $report;
		}

		return true;
	}

	/**
	 * Checks if $plugins meets the requirement by $dep.
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param array $plugins A list of plugins as returned by get_installed_plugins();
	 * @param bool  $inverse Inverse the results to use as a conflicts.
	 * @return bool
	 */
	private function _checkDepPlugin(array $dep, array $plugins, $inverse = false) {
		$r = elgg_check_plugins_provides('plugin', $dep['name'], $dep['version'], $dep['comparison']);

		if ($inverse) {
			$r = !$r;
		}

		return $r;
	}

	/**
	 * Checks if $elgg_version meets the requirement by $dep.
	 *
	 * @param array $dep          An Elgg manifest.xml deps array
	 * @param array $elgg_version An Elgg version (either YYYYMMDDXX or X.Y.Z)
	 * @param bool  $inverse      Inverse the result to use as a conflicts.
	 * @return bool
	 */
	private function _checkDepElgg(array $dep, $elgg_version, $inverse = false) {
		$r = version_compare($elgg_version, $dep['version'], $dep['comparison']);

		if ($inverse) {
			$r = !$r;
		}

		return $r;
	}

	/**
	 * Checks if the PHP extension in $dep is loaded.
	 *
	 * @todo Can this be merged with the plugin checker?
	 *
	 * @param array $dep An Elgg manifest.xml deps array
	 * @return bool
	 */
	private function _checkDepPhpExtension(array $dep) {
		$name = $dep['name'];
		$version = $dep['version'];
		$comparison = $dep['comparison'];

		// not enabled.
		$r = extension_loaded($name);

		// enabled. check version.
		$ext_version = phpversion($name);

		if ($version && !version_compare($ext_version, $version, $comparison)) {
			$r = false;
		}

		// some php extensions can be emulated, so check provides.
		if ($r == false) {
			$r = elgg_check_plugins_provides('php_extension', $name, $version, $comparison);
		}

		return $r;
	}

	/**
	 * Check if the PHP ini setting satisfies $dep.
	 *
	 * @param array $dep An Elgg manifest.xml deps array
	 * @return bool
	 */
	private function _checkDepPhpIni($dep) {
		$name = $dep['name'];
		$value = $dep['value'];
		$comparison = $dep['comparison'];

		// ini_get() normalizes truthy values to 1 but falsey values to 0 or ''.
		// version_compare() considers '' < 0, so normalize '' to 0.
		// ElggPluginManifest normalizes all bool values and '' to 1 or 0.
		$setting = ini_get($name);

		if ($setting === '') {
			$setting = 0;
		}

		$r = version_compare($setting, $value, $comparison);

		return $r;
	}


	/**************************************
	 * Detailed reports for requirements. *
	 **************************************/


	/**
	 * Returns a report of the dependencies with human
	 * readable statuses.
	 *
	 * @return array
	 */
	public function getDependenciesReport() {
		$requires = $this->getManifest()->getRequires();
		$conflicts = $this->getManifest()->getConflicts();
		$enabled_plugins = get_installed_plugins('enabled');

		$status = true;
		$messages = array();

		$return = array(
			array(
				'type' => 'requires',
				'dep' => $dep,
				'status' => 'bool',
				'comment' => ''
			)
		);

		foreach ($requires as $require) {
			switch ($require['type']) {
				case 'elgg_version':
					$result = $this->_checkRequiresElgg($require, get_version());
					break;

				case 'elgg_release':
					$result = $this->_checkRequiresElgg($require, get_version(true));
					break;

				case 'plugin':
					$result = $this->_checkDepsPlugin($require, $enabled_plugins);
					break;

				case 'php_extension':
					$result = $this->_checkRequiresPhpExtension($require);
					break;

				case 'php_ini':
					$result = $this->_checkRequiresPhpIni($require);
					break;

				default:
					$result = array(
						'status' => false,
						'message' => elgg_echo('ElggPluginPackage:UnknownDep',
										array($require['type'], $this->getManifest()->getPluginID()))
					);
					break;
			}

			if (!$result['status']) {
				$status = false;
				$messages[] = $result['message'];
			}
		}

		foreach ($conflicts as $conflict) {

		}

		$return = array(
			'status' => $status,
			'messages' => $messages
		);

		return $return;
	}

	/**
	 * Checks if $plugins meets the requirement by $require.
	 *
	 * Returns an array in the form array('status' => bool, 'message' => 'Any messages')
	 *
	 * @param array $require An Elgg manifest.xml requires array
	 * @param array $plugins A list of plugins as returned by get_installed_plugins();
	 * @return array
	 */
	private function _checkRequiresPlugin(array $require, array $plugins = array()) {
		$status = true;
		$message = '';

		$name = $require['name'];
		$version = $require['version'];
		$comparison = $require['comparison'];

		// not enabled.
		if (!array_key_exists($name, $plugins)) {
			$status = false;

			if ($version) {
				$message = elgg_echo("ElggPluginPackage:Requires:Plugin:NotEnabled:$comparison",
							array($this->getManifest()->getPluginID(), $name, $version));
			} else {
				$message = elgg_echo('ElggPluginPackage:Requires:Plugin:NotEnabled:NoVersion',
							array($this->getManifest()->getPluginID(), $name));
			}
		}

		// enabled. check version.
		if ($status != false) {
			$requires_plugin_info = $plugins[$name];

			//@todo boot strapping until we can migrate everything over to ElggPluginPackage.
			$plugin_package = new ElggPluginPackage($name);
			$plugin_version = $plugin_package->getManifest()->getVersion();

			if ($version && !version_compare($plugin_version, $version, $comparison)) {
				$status = false;

				$message = elgg_echo("ElggPluginPackage:Requires:Plugin:$comparison",
								array($this->getManifest()->getPluginID(), $name, $version, $plugin_version));
			}
		}

		// if all else fails check with the provides
		if ($status == false) {
			if (elgg_check_plugins_provides('plugin', $name)) {
				// it's provided. check version if asked.
				$status = true;
				$message = '';

				if ($version && !elgg_check_plugins_provides('plugin', $name, $version, $comparison)) {
						// change the message to something more meaningful
						$provide = elgg_get_plugins_provides('plugin', $name);
						$plugin_version = "{$provide['provided_by']}:$name={$provide['version']}";

						$status = false;
						$message = elgg_echo("ElggPluginPackage:Requires:Plugin:$comparison",
								array($this->getManifest()->getPluginID(), $name, $version, $plugin_version));
				}
			}
		}

		return array(
			'status' => $status,
			'message' => $message
		);
	}

	/**
	 * Checks if $elgg_version meets the requirement by $require.
	 *
	 * Returns an array in the form array('status' => bool, 'message' => 'Any messages')
	 *
	 * @param array $require      An Elgg manifest.xml requires array
	 * @param array $elgg_version An Elgg version (either YYYYMMDDXX or X.Y.Z)
	 * @return array
	 */
	private function _checkRequiresElgg(array $require, $elgg_version) {
		$status = true;
		$message = '';
		$version = $require['version'];
		$comparison = $require['comparison'];

		if (!version_compare($elgg_version, $version, $comparison)) {
			$status = false;
			$message = elgg_echo("ElggPluginPackage:Requires:Elgg:$comparison",
							array($this->getManifest()->getPluginID(), $version));
		}

		return array(
			'status' => $status,
			'message' => $message
		);
	}

	/**
	 * Checks if the PHP extension in $require is loaded.
	 *
	 * @todo Can this be merged with the plugin checker?
	 *
	 * @param array $require An Elgg manifest.xml deps array
	 * @return array
	 */
	private function _checkRequiresPhpExtension($require) {
		$status = true;
		$message = '';

		$name = $require['name'];
		$version = $require['version'];
		$comparison = $require['comparison'];

		// not enabled.
		if (!extension_loaded($name)) {
			$status = false;
			if ($version) {
				$message = elgg_echo("ElggPluginPackage:Requires:PhpExtension:NotInstalled:$comparison",
							array($this->getManifest()->getPluginID(), $name, $version));
			} else {
				$message = elgg_echo('ElggPluginPackage:Requires:PhpExtension:NotInstalled:NoVersion',
							array($this->getManifest()->getPluginID(), $name));
			}
		}

		// enabled. check version.
		if ($status != false) {
			$ext_version = phpversion($name);

			if ($version && !version_compare($ext_version, $version, $comparison)) {
				$status = false;
				$message = elgg_echo("ElggPluginPackage:Requires:PhpExtension:$comparison",
								array($this->getManifest()->getPluginID(), $name, $version));
			}
		}

		// some php extensions can be emulated, so check provides.
		if ($status == false) {
			if (elgg_check_plugins_provides('php_extension', $name)) {
				// it's provided. check version if asked.
				$status = true;
				$message = '';

				if ($version && !elgg_check_plugins_provides('php_extension', $name, $version, $comparison)) {
						// change the message to something more meaningful
						$provide = elgg_get_plugins_provides('php_extension', $name);
						$plugin_version = "{$provide['provided_by']}:$name={$provide['version']}";

						$status = false;
						$message = elgg_echo("ElggPluginPackage:Requires:PhpExtension:$comparison",
								array($this->getManifest()->getPluginID(), $name, $version, $plugin_version));
				}
			}
		}

		return array(
			'status' => $status,
			'message' => $message
		);
	}


	/**
	 * Check if the PHP ini setting satisfies $require.
	 *
	 * @param array $require An Elgg manifest.xml requires array
	 * @return array
	 */
	private function _checkRequiresPhpIni($require) {
		$status = true;
		$message = '';

		$name = $require['name'];
		$value = $require['value'];
		$comparison = $require['comparison'];

		// ini_get() normalizes truthy values to 1 but falsey values to 0 or ''.
		// version_compare() considers '' < 0, so normalize '' to 0.
		// ElggPluginManifest normalizes all bool values and '' to 1 or 0.
		$setting = ini_get($name);

		if ($setting === '') {
			$setting = 0;
		}

		if (!version_compare($setting, $value, $comparison)) {
			$status = false;
			$message = elgg_echo("ElggPluginPackage:Requires:PhpIni:$comparison",
					array($this->getManifest()->getPluginID(), $name, $value, $setting));
		}

		return array(
			'status' => $status,
			'message' => $message
		);
	}

	/**
	 * Activate the plugin.
	 *
	 * @note This method is activate() to avoid clashing with ElggEntity::enable()
	 *
	 * @return bool
	 */
	public function activate() {
		return enable_plugin($this->getID());
	}

	/**
	 * Deactivate the plugin.
	 *
	 * @note This method is deactivate() to avoid clashing with ElggEntity::disable()
	 *
	 * @return bool
	 */
	public function deactivate() {
		return disable_plugin($this->getID());
	}

	/**
	 * Returns the Plugin ID
	 *
	 * @return string
	 */
	public function getID() {
		return $this->id;
	}

}