<?php

namespace Elgg\Plugins;

use Elgg\UnitTestCase;

/**
 * Common operations during plugin testing
 */
trait PluginTesting {

	/**
	 * @var array
	 */
	protected $started_plugins = [];

	/**
	 * Returns plugin's root path or false if not called from a plugin directory
	 *
	 * @return string|false
	 */
	public function getPath() {
		$reflector = new \ReflectionObject($this);
		$fn = $reflector->getFileName();

		$path = \Elgg\Project\Paths::sanitize(dirname($fn));
		$plugins_path = \Elgg\Project\Paths::sanitize(elgg_get_plugins_path());

		if (strpos($path, $plugins_path) === 0) {
			$relative_path = substr($path, strlen($plugins_path));
			list($plugin_id,) = explode('/', $relative_path, 2);

			return $plugins_path . $plugin_id . '/';
		}

		return false;
	}

	/**
	 * Returns the plugin id if called from a plugin, or false otherwise
	 *
	 * @return string|false
	 */
	public function getPluginID() {

		$path = $this->getPath();
		if (empty($path)) {
			return false;
		}

		$parts = explode('/', $path);
		$parts = array_filter($parts);

		return array_pop($parts);
	}

	/**
	 * Start a plugin that extending test belongs to
	 * Calling this method should only be required in unit test cases
	 *
	 * @param string $plugin_id         Start a plugin
	 * @param bool   $activate_requires Activate required plugins
	 * @param bool   $activate_suggests Activate suggested plugins
	 *
	 * @return \ElggPlugin|null
	 *
	 * @return \ElggPlugin|null|void
	 * @throws \InvalidParameterException
	 * @throws \PluginException
	 */
	public function startPlugin($plugin_id = null, $activate_requires = true, $activate_suggests = false) {
		if (!isset($plugin_id)) {
			$plugin_id = $this->getPluginID();
		}

		if (!$plugin_id) {
			return null;
		}

		$plugin = \ElggPlugin::fromId($plugin_id);
		if (!$plugin) {
			return null;
		}

		if (in_array($plugin_id, $this->started_plugins)) {
			return null;
		}

		if ($this instanceof UnitTestCase) {
			// @todo Decouple plugin injection logic from this trait

			$svc = _elgg_services()->plugins;
			/* @var $svc \Elgg\Mocks\Database\Plugins */

			$svc->addTestingPlugin($plugin);
		}

		if ($activate_requires) {
			$requires = $plugin->getManifest()->getRequires();
			foreach ($requires as $require) {
				if ($require['type'] === 'plugin') {
					$this->startPlugin($require['name'], $activate_requires, $activate_suggests);
				}
			}
		}

		if ($activate_suggests) {
			$suggests = $plugin->getManifest()->getSuggests();
			foreach ($suggests as $suggest) {
				if ($suggest['type'] === 'plugin') {
					$this->startPlugin($suggest['name'], $activate_requires, $activate_suggests);
				}
			}
		}

		$plugin->register();
		$setup = $plugin->boot();
		if ($setup instanceof \Closure) {
			$setup();
		}

		_elgg_rebuild_public_container();

		$plugin->init();

		$this->started_plugins[] = $plugin_id;

		return $plugin;
	}

}
