<?php

namespace Elgg\Plugins;

/**
 * Common operations during plugin testing
 */
trait PluginTesting {

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
	 * @param null $flags Start flags
	 *
	 * @return \ElggPlugin|null
	 *
	 * @return \ElggPlugin|null|void
	 * @throws \InvalidParameterException
	 * @throws \PluginException
	 */
	public function startPlugin() {
		$plugin_id = $this->getPluginID();
		if (!$plugin_id) {
			return null;
		}

		$plugin = \ElggPlugin::fromId($plugin_id);
		if (!$plugin) {
			return null;
		}

		// @todo Resolve plugin dependencies and activate required plugins

		$setup = $plugin->boot();
		if ($setup instanceof \Closure) {
			$setup();
		}

		$plugin->init();
		
		return $plugin;
	}

}
