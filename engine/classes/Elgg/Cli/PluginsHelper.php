<?php

namespace Elgg\Cli;

/**
 * Trait shared by CLI commands to simplify plugin management
 */
trait PluginsHelper {

	/**
	 * Activate a plugin
	 *
	 * @param string $id    Plugin ID
	 * @param bool   $force Resolve conflicts
	 *
	 * @return bool
	 * @throws \InvalidParameterException
	 * @throws \PluginException
	 */
	public function activate($id, $force = false) {

		$plugin = elgg_get_plugin_from_id($id);
		if (!$plugin) {
			throw new \InvalidParameterException("No plugin with '$id'' found");
		}

		if (!$plugin->getManifest()) {
			throw new \InvalidParameterException("Plugin manifest for '$id' is invalid");
		}

		if (!$force) {
			return $plugin->activate();
		}

		$conflicts = $this->getConflicts($id);
		foreach ($conflicts as $conflict) {
			$this->deactivate($conflict, true);
		}

		$requires = $this->getRequires($id);
		foreach ($requires as $require) {
			$this->activate($require, true);
		}

		return $plugin->activate();
	}

	/**
	 * Deactivate a plugin
	 *
	 * @param string $id    Plugin ID
	 * @param bool   $force Also deactivate dependents
	 *
	 * @return bool
	 * @throws \InvalidParameterException
	 * @throws \PluginException
	 */
	public function deactivate($id, $force = false) {
		$plugin = elgg_get_plugin_from_id($id);
		if (!$plugin) {
			throw new \InvalidParameterException("No plugin with '$id'' found");
		}

		if (!$plugin->getManifest()) {
			throw new \InvalidParameterException("Plugin manifest for '$id' is invalid");
		}

		if (!$force) {
			return $plugin->deactivate();
		}

		$dependents = $this->getDependents($id);
		foreach ($dependents as $dependent) {
			$this->deactivate($dependent);
		}

		return $plugin->deactivate();
	}

	/**
	 * Get plugin dependents
	 *
	 * @param string $id Plugin ID
	 *
	 * @return string[]
	 */
	public function getDependents($id) {
		$result = [];

		$active_plugins = elgg_get_plugins();

		foreach ($active_plugins as $plugin) {
			$manifest = $plugin->getManifest();
			if (!$manifest) {
				continue;
			}

			$requires = $manifest->getRequires();

			foreach ($requires as $required) {
				if ($required['type'] == 'plugin' && $required['name'] == $id) {
					// there are active dependents
					$result[] = $manifest->getPluginID();
				}
			}
		}

		return $result;
	}

	/**
	 * Get conflicting plugins
	 *
	 * @param string $id Plugin ID
	 *
	 * @return string[]
	 */
	public function getConflicts($id) {
		$result = [];

		$plugin = elgg_get_plugin_from_id($id);

		$conflicts = $plugin->getManifest()->getConflicts();

		foreach ($conflicts as $conflict) {
			if ($conflict['type'] === 'plugin') {
				$name = $conflict['name'];
				if (elgg_get_plugin_from_id($name)) {
					$result[] = $name;
				}
			}
		}

		return $result;
	}

	/**
	 * Get required plugins
	 *
	 * @param string $id Plugin ID
	 *
	 * @return string[]
	 */
	public function getRequires($id) {
		$result = [];

		$plugin = elgg_get_plugin_from_id($id);

		$requires = $plugin->getManifest()->getRequires();

		foreach ($requires as $require) {
			if ($require['type'] === 'plugin') {
				$name = $require['name'];
				if (elgg_get_plugin_from_id($name)) {
					$result[] = $name;
				}
			}
		}

		return $result;
	}
}
