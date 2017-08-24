<?php
/**
 *
 */

namespace Elgg\Plugins;

/**
 * Common operations during plugin testing
 */
trait PluginTesting {

	/**
	 * Returns plugin's root path or false if not called from a plugin directory
	 * @return string|false
	 */
	public function getPath() {
		$reflector = new \ReflectionObject($this);
		$fn = $reflector->getFileName();

		$path = sanitise_filepath(dirname($fn));
		$plugins_path = sanitise_filepath(elgg_get_plugins_path());

		if (strpos($path, $plugins_path) === 0) {
			$relative_path = substr($path, strlen($plugins_path));
			list($plugin_id, ) = explode('/', $relative_path, 2);
			return $plugins_path . $plugin_id . '/';
		}

		return false;
	}
}