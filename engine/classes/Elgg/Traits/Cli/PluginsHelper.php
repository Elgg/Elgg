<?php

namespace Elgg\Traits\Cli;

use Elgg\Exceptions\PluginException;
use Elgg\Exceptions\UnexpectedValueException;

/**
 * Trait shared by CLI commands to simplify plugin management
 *
 * @internal
 */
trait PluginsHelper {

	/**
	 * Activate a plugin
	 *
	 * @param string $id    Plugin ID
	 * @param bool   $force Resolve conflicts
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function activate(string $id, bool $force = false): bool {
		$split = explode(':', $id);

		$plugin_id = $split[0] ?? $id;
		$priority = $split[1] ?? null;

		$plugin = elgg_get_plugin_from_id($plugin_id);
		if (!$plugin instanceof \ElggPlugin) {
			throw new UnexpectedValueException(elgg_echo('PluginException:InvalidID', [$plugin_id]));
		}
		
		if ($plugin->isActive()) {
			return true;
		}
		
		if (isset($priority)) {
			$plugin->setPriority($priority);
		}

		if (!$force) {
			try {
				return $plugin->activate();
			} catch (PluginException $e) {
				return false;
			}
		}

		$conflicts = $this->getConflicts($plugin_id);
		foreach ($conflicts as $conflict) {
			$this->deactivate($conflict, true);
		}

		$requires = $this->getRequires($plugin_id);
		foreach ($requires as $require) {
			$this->activate($require, true);
		}

		try {
			return $plugin->activate();
		} catch (PluginException $e) {
			return false;
		}
	}

	/**
	 * Deactivate a plugin
	 *
	 * @param string $id    Plugin ID
	 * @param bool   $force Also deactivate dependents
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function deactivate(string $id, bool $force = false): bool {
		$plugin = elgg_get_plugin_from_id($id);
		if (!$plugin instanceof \ElggPlugin) {
			throw new UnexpectedValueException(elgg_echo('PluginException:InvalidID', [$id]));
		}

		if (!$plugin->isActive()) {
			return true;
		}

		if (!$force) {
			try {
				return $plugin->deactivate();
			} catch (PluginException $e) {
				return false;
			}
		}

		$dependents = $this->getDependents($id);
		foreach ($dependents as $dependent) {
			$this->deactivate($dependent, true);
		}

		try {
			return $plugin->deactivate();
		} catch (PluginException $e) {
			return false;
		}
	}

	/**
	 * Get plugin dependents
	 *
	 * @param string $id Plugin ID
	 *
	 * @return string[]
	 */
	protected function getDependents(string $id): array {
		$dependents = [];

		$active_plugins = elgg_get_plugins();

		foreach ($active_plugins as $plugin) {
			$dependencies = $plugin->getDependencies();
			if (!array_key_exists($id, $dependencies)) {
				continue;
			}
			
			if (elgg_extract('must_be_active', $dependencies[$id], true)) {
				$dependents[] = $plugin->getID();
			}
		}

		return $dependents;
	}

	/**
	 * Get conflicting plugins
	 *
	 * @param string $id Plugin ID
	 *
	 * @return string[]
	 */
	public function getConflicts(string $id): array {
		$result = [];

		$plugin = elgg_get_plugin_from_id($id);
		$conflicts = $plugin->getConflicts();

		foreach ($conflicts as $plugin_id => $plugin_version) {
			// @todo need to validate version
			if (elgg_get_plugin_from_id($plugin_id)) {
				$result[] = $plugin_id;
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
	public function getRequires(string $id): array {
		$result = [];

		$plugin = elgg_get_plugin_from_id($id);
		foreach ($plugin->getDependencies() as $plugin_id => $config) {
			if (!elgg_extract('must_be_active', $config, true)) {
				continue;
			}
			
			if (!elgg_get_plugin_from_id($plugin_id)) {
				continue;
			}
			
			$result[] = $plugin_id;
		}

		return $result;
	}
}
