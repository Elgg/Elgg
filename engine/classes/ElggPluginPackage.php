<?php

/**
 * Manages plugin packages under mod.
 *
 * @todo       This should eventually be merged into \ElggPlugin.
 * Currently \ElggPlugin objects are only used to get and save
 * plugin settings and user settings, so not every plugin
 * has an \ElggPlugin object.  It's not implemented in \ElggPlugin
 * right now because of conflicts with at least the constructor,
 * enable(), disable(), and private settings.
 *
 * Around 1.9 or so we should each plugin over to using
 * \ElggPlugin and merge \ElggPluginPackage and \ElggPlugin.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
class ElggPluginPackage {

	const STATIC_CONFIG_FILENAME = 'elgg-plugin.php';

	/**
	 * The required files in the package
	 *
	 * @var array
	 */
	private $requiredFiles = [
		'manifest.xml'
	];

	/**
	 * The optional files that can be read and served through the markdown page handler
	 * @var array
	 */
	private $textFiles = [
		'README.txt',
		'CHANGES.txt',
		'INSTALL.txt',
		'COPYRIGHT.txt',
		'LICENSE.txt',
		'README',
		'README.md',
		'README.markdown'
	];

	/**
	 * Valid types for provides.
	 *
	 * @var array
	 */
	private $providesSupportedTypes = [
		'plugin',
		'php_extension'
	];

	/**
	 * The type of requires/conflicts supported
	 *
	 * @var array
	 */
	private $depsSupportedTypes = [
		'elgg_release',
		'php_version',
		'php_extension',
		'php_ini',
		'plugin',
		'priority',
	];

	/**
	 * An invalid plugin error.
	 */
	private $errorMsg = '';

	/**
	 * The plugin's manifest object
	 *
	 * @var \ElggPluginManifest
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
	 * @throws PluginException
	 */
	public function __construct($plugin, $validate = true) {
		$plugin_path = _elgg_config()->plugins_path;
		// @todo wanted to avoid another is_dir() call here.
		// should do some profiling to see how much it affects
		if (strpos($plugin, $plugin_path) === 0 || is_dir($plugin)) {
			// this is a path
			$path = \Elgg\Project\Paths::sanitize($plugin);

			// the id is the last element of the array
			$path_array = explode('/', trim($path, '/'));
			$id = array_pop($path_array);
		} else {
			// this is a plugin id
			// strict plugin names
			if (preg_match('/[^a-z0-9\.\-_]/i', $plugin)) {
				$msg = elgg_echo('PluginException:InvalidID', [$plugin]);
				throw PluginException::factory('InvalidID', null, $msg);
			}

			$path = "{$plugin_path}$plugin/";
			$id = $plugin;
		}

		if (!is_dir($path)) {
			$msg = elgg_echo('PluginException:InvalidPath', [$path]);
			throw PluginException::factory('InvalidPath', null, $msg);
		}

		$this->path = $path;
		$this->id = $id;

		if ($validate && !$this->isValid()) {
			if ($this->errorMsg) {
				$msg = elgg_echo('PluginException:InvalidPlugin:Details', [$plugin, $this->errorMsg]);
				throw PluginException::factory('InvalidPluginDetails', null, $msg);
			} else {
				$msg = elgg_echo('PluginException:InvalidPlugin', [$plugin]);
				throw PluginException::factory('InvalidPlugin', null, $msg);
			}
		}
	}

	/********************************
	 * Validation and sanity checks *
	 ********************************/

	/**
	 * Checks if this is a valid Elgg plugin.
	 *
	 * Checks for requires files as defined at the start of this
	 * class.  Will check require manifest fields via \ElggPluginManifest
	 * for Elgg 1.8 plugins.
	 *
	 * @note This doesn't check dependencies or conflicts.
	 * Use {@link \ElggPluginPackage::canActivate()} or
	 * {@link \ElggPluginPackage::checkDependencies()} for that.
	 *
	 * @return bool
	 */
	public function isValid() {
		if (!isset($this->valid)) {
			$this->valid = $this->validate();
		}

		return $this->valid;
	}

	/**
	 * @return bool
	 */
	private function validate() {
		// check required files.
		$have_req_files = true;
		foreach ($this->requiredFiles as $file) {
			if (!is_readable($this->path . $file)) {
				$have_req_files = false;
				$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:MissingFile', [$file]);

				return false;
			}
		}

		// check required files
		if (!$have_req_files) {
			return $this->valid = false;
		}

		// check for valid manifest.
		if (!$this->loadManifest()) {
			return false;
		}

		if (!$this->isNamedCorrectly()) {
			return false;
		}

		// can't require or conflict with yourself or something you provide.
		// make sure provides are all valid.
		if (!$this->hasSaneDependencies()) {
			return false;
		}

		if (!$this->hasReadableConfigFile()) {
			return false;
		}

		return true;
	}

	/**
	 * Check that, if the plugin has a static config file, it is readable. We wait to read the contents
	 * because we don't want to risk crashing the whole plugins page.
	 *
	 * @return bool
	 */
	private function hasReadableConfigFile() {
		$file = "{$this->path}/" . self::STATIC_CONFIG_FILENAME;
		if (!is_file($file)) {
			return true;
		}

		if (is_readable($file)) {
			return true;
		}

		$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:UnreadableConfig');

		return false;
	}

	/**
	 * Check that the plugin is installed in the directory with name specified
	 * in the manifest's "id" element.
	 *
	 * @return bool
	 */
	private function isNamedCorrectly() {
		$manifest = $this->getManifest();
		if ($manifest) {
			$required_id = $manifest->getID();
			if (!empty($required_id) && ($required_id !== $this->id)) {
				$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:InvalidId', [$required_id]);

				return false;
			}
		}

		return true;
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
	private function hasSaneDependencies() {
		// protection against plugins with no manifest file
		if (!$this->getManifest()) {
			return false;
		}

		// Note: $conflicts and $requires are not unused. They're called dynamically
		$conflicts = $this->getManifest()->getConflicts();
		$requires = $this->getManifest()->getRequires();
		$provides = $this->getManifest()->getProvides();

		foreach ($provides as $provide) {
			// only valid provide types
			if (!in_array($provide['type'], $this->providesSupportedTypes)) {
				$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:InvalidProvides', [
					$provide['type'],
				]);

				return false;
			}

			// doesn't conflict or require any of its provides
			$name = $provide['name'];
			foreach (['conflicts', 'requires'] as $dep_type) {
				foreach (${$dep_type} as $dep) {
					if (!in_array($dep['type'], $this->depsSupportedTypes)) {
						$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:InvalidDependency', [
							$dep['type'],
						]);

						return false;
					}

					// make sure nothing is providing something it conflicts or requires.
					if (isset($dep['name']) && $dep['name'] == $name) {
						$version_compare = version_compare($provide['version'], $dep['version'], $dep['comparison']);

						if ($version_compare) {
							$this->errorMsg = elgg_echo('ElggPluginPackage:InvalidPlugin:CircularDep', [
								$dep['type'],
								$dep['name'],
								$this->id,
							]);

							return false;
						}
					}
				}
			}
		}

		return true;
	}


	/************
	 * Manifest *
	 ************/

	/**
	 * Returns a parsed manifest file.
	 *
	 * @return \ElggPluginManifest
	 */
	public function getManifest() {
		if (!$this->manifest) {
			if (!$this->loadManifest()) {
				return false;
			}
		}

		return $this->manifest;
	}

	/**
	 * Loads the manifest into this->manifest as an
	 * \ElggPluginManifest object.
	 *
	 * @return bool
	 */
	private function loadManifest() {
		$file = $this->path . 'manifest.xml';

		try {
			$this->manifest = new \ElggPluginManifest($file, $this->id);
		} catch (Exception $e) {
			elgg_log($e, \Psr\Log\LogLevel::ERROR);

			$this->errorMsg = $e->getMessage();

			return false;
		}

		if ($this->manifest instanceof \ElggPluginManifest) {
			return true;
		}

		$this->errorMsg = elgg_echo('unknown_error');

		return false;
	}

	/****************
	 * Readme Files *
	 ***************/

	/**
	 * Returns an array of present and readable text files
	 *
	 * @return array
	 */
	public function getTextFilenames() {
		return $this->textFiles;
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
	 *    'type' => requires|conflicts,
	 *    'dep' => array( dependency array ),
	 *    'status' => bool if depedency is met,
	 *    'comment' => optional comment to display to the user.
	 * )
	 *
	 * @param bool $full_report Return a full report.
	 *
	 * @return bool|array
	 */
	public function checkDependencies($full_report = false) {
		// Note: $conflicts and $requires are not unused. They're called dynamically
		$requires = $this->getManifest()->getRequires();
		$conflicts = $this->getManifest()->getConflicts();

		$enabled_plugins = elgg_get_plugins('active');
		$this_id = $this->getID();
		$report = [];

		// first, check if any active plugin conflicts with us.
		foreach ($enabled_plugins as $plugin) {
			$temp_conflicts = [];
			$temp_manifest = $plugin->getManifest();
			if ($temp_manifest instanceof \ElggPluginManifest) {
				$temp_conflicts = $plugin->getManifest()->getConflicts();
			}
			foreach ($temp_conflicts as $conflict) {
				if ($conflict['type'] == 'plugin' && $conflict['name'] == $this_id) {
					$result = $this->checkDepPlugin($conflict, $enabled_plugins, false);

					// rewrite the conflict to show the originating plugin
					$conflict['name'] = $plugin->getDisplayName();

					if (!$full_report && !$result['status']) {
						$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getManifest()->getID());
						$link = elgg_view('output/url', [
							'text' => $plugin->getDisplayName(),
							'href' => "#$css_id",
						]);

						$key = 'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin';
						$this->errorMsg = elgg_echo($key, [$link]);

						return $result['status'];
					} else {
						$report[] = [
							'type' => 'conflicted',
							'dep' => $conflict,
							'status' => $result['status'],
							'value' => $this->getManifest()->getVersion()
						];
					}
				}
			}
		}

		$check_types = ['requires', 'conflicts'];

		if ($full_report) {
			// Note: $suggests is not unused. It's called dynamically
			$suggests = $this->getManifest()->getSuggests();
			$check_types[] = 'suggests';
		}

		foreach ($check_types as $dep_type) {
			$inverse = ($dep_type == 'conflicts') ? true : false;

			foreach (${$dep_type} as $dep) {
				switch ($dep['type']) {
					case 'elgg_release':
						$result = $this->checkDepElgg($dep, elgg_get_version(true), $inverse);
						break;

					case 'plugin':
						$result = $this->checkDepPlugin($dep, $enabled_plugins, $inverse);
						break;

					case 'priority':
						$result = $this->checkDepPriority($dep, $enabled_plugins, $inverse);
						break;

					case 'php_version':
						$result = $this->checkDepPhpVersion($dep, $inverse);
						break;

					case 'php_extension':
						$result = $this->checkDepPhpExtension($dep, $inverse);
						break;

					case 'php_ini':
						$result = $this->checkDepPhpIni($dep, $inverse);
						break;

					default:
						$result = null;//skip further check
						break;
				}

				if ($result !== null) {
					// unless we're doing a full report, break as soon as we fail.
					if (!$full_report && !$result['status']) {
						$type = $dep['type'];
						
						if ($type === 'priority') {
							$text = "{$dep['priority']} {$dep['plugin']}";
						} else {
							$text = $dep['name'];
						}
						
						$this->errorMsg = elgg_echo('admin:plugins:label:missing_dependency', ["{$type}: {$text}"]);

						return $result['status'];
					} else {
						// build report element and comment
						$report[] = [
							'type' => $dep_type,
							'dep' => $dep,
							'status' => $result['status'],
							'value' => $result['value']
						];
					}
				}
			}
		}

		if ($full_report) {
			// add provides to full report
			$provides = $this->getManifest()->getProvides();

			foreach ($provides as $provide) {
				$report[] = [
					'type' => 'provides',
					'dep' => $provide,
					'status' => true,
					'value' => ''
				];
			}

			return $report;
		}

		return true;
	}

	/**
	 * Checks if $plugins meets the requirement by $dep.
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param array $plugins A list of plugins as returned by elgg_get_plugins();
	 * @param bool  $inverse Inverse the results to use as a conflicts.
	 *
	 * @return bool
	 */
	private function checkDepPlugin(array $dep, array $plugins, $inverse = false) {
		$r = _elgg_services()->plugins->checkProvides('plugin', $dep['name'], $dep['version'], $dep['comparison']);

		if ($inverse) {
			$r['status'] = !$r['status'];
		}

		return $r;
	}

	/**
	 * Checks if $plugins meets the requirement by $dep.
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param array $plugins A list of plugins as returned by elgg_get_plugins();
	 * @param bool  $inverse Inverse the results to use as a conflicts.
	 *
	 * @return bool
	 */
	private function checkDepPriority(array $dep, array $plugins, $inverse = false) {
		// grab the \ElggPlugin using this package.
		$plugin_package = elgg_get_plugin_from_id($this->getID());
		if (!$plugin_package) {
			return [
				'status' => true,
				'value' => 'uninstalled'
			];
		}

		$test_plugin = elgg_get_plugin_from_id($dep['plugin']);

		// If this isn't a plugin or the plugin isn't installed or active
		// priority doesn't matter. Use requires to check if a plugin is active.
		if (!$test_plugin || !$test_plugin->isActive()) {
			return [
				'status' => true,
				'value' => 'uninstalled'
			];
		}

		$plugin_priority = $plugin_package->getPriority();
		$test_plugin_priority = $test_plugin->getPriority();

		switch ($dep['priority']) {
			case 'before':
				$status = $plugin_priority < $test_plugin_priority;
				break;

			case 'after':
				$status = $plugin_priority > $test_plugin_priority;
				break;

			default;
				$status = false;
		}

		// get the current value
		if ($plugin_priority < $test_plugin_priority) {
			$value = 'before';
		} else {
			$value = 'after';
		}

		if ($inverse) {
			$status = !$status;
		}

		return [
			'status' => $status,
			'value' => $value
		];
	}

	/**
	 * Checks if $elgg_version meets the requirement by $dep.
	 *
	 * @param array $dep          An Elgg manifest.xml deps array
	 * @param array $elgg_version An Elgg version (either YYYYMMDDXX or X.Y.Z)
	 * @param bool  $inverse      Inverse the result to use as a conflicts.
	 *
	 * @return bool
	 */
	private function checkDepElgg(array $dep, $elgg_version, $inverse = false) {
		$status = version_compare($elgg_version, $dep['version'], $dep['comparison']);

		if ($inverse) {
			$status = !$status;
		}

		return [
			'status' => $status,
			'value' => $elgg_version
		];
	}

	/**
	 * Checks if $php_version meets the requirement by $dep.
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param bool  $inverse Inverse the result to use as a conflicts.
	 *
	 * @return bool
	 */
	private function checkDepPhpVersion(array $dep, $inverse = false) {
		$php_version = phpversion();
		$status = version_compare($php_version, $dep['version'], $dep['comparison']);

		if ($inverse) {
			$status = !$status;
		}

		return [
			'status' => $status,
			'value' => $php_version
		];
	}

	/**
	 * Checks if the PHP extension in $dep is loaded.
	 *
	 * @todo Can this be merged with the plugin checker?
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param bool  $inverse Inverse the result to use as a conflicts.
	 *
	 * @return array An array in the form array(
	 *    'status' => bool
	 *    'value' => string The version provided
	 * )
	 */
	private function checkDepPhpExtension(array $dep, $inverse = false) {
		$name = $dep['name'];
		$version = $dep['version'];
		$comparison = $dep['comparison'];

		// not enabled.
		$status = extension_loaded($name);

		// enabled. check version.
		$ext_version = phpversion($name);

		if ($status) {
			// some extensions (like gd) don't provide versions. neat.
			// don't check version info and return a lie.
			if ($ext_version && $version) {
				$status = version_compare($ext_version, $version, $comparison);
			}

			if (!$ext_version) {
				$ext_version = '???';
			}
		}

		// some php extensions can be emulated, so check provides.
		if ($status == false) {
			$provides = _elgg_services()->plugins->checkProvides('php_extension', $name, $version, $comparison);
			$status = $provides['status'];
			$ext_version = $provides['value'];
		}

		if ($inverse) {
			$status = !$status;
		}

		return [
			'status' => $status,
			'value' => $ext_version
		];
	}

	/**
	 * Check if the PHP ini setting satisfies $dep.
	 *
	 * @param array $dep     An Elgg manifest.xml deps array
	 * @param bool  $inverse Inverse the result to use as a conflicts.
	 *
	 * @return bool
	 */
	private function checkDepPhpIni($dep, $inverse = false) {
		$name = $dep['name'];
		$value = $dep['value'];
		$comparison = $dep['comparison'];

		// ini_get() normalizes truthy values to 1 but falsey values to 0 or ''.
		// version_compare() considers '' < 0, so normalize '' to 0.
		// \ElggPluginManifest normalizes all bool values and '' to 1 or 0.
		$setting = ini_get($name);

		if ($setting === '') {
			$setting = 0;
		}

		$status = version_compare($setting, $value, $comparison);

		if ($inverse) {
			$status = !$status;
		}

		return [
			'status' => $status,
			'value' => $setting
		];
	}

	/**
	 * Returns the Plugin ID
	 *
	 * @return string
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * Returns the last error message.
	 *
	 * @return string
	 */
	public function getError() {
		return $this->errorMsg;
	}
}
