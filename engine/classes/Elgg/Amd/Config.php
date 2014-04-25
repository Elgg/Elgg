<?php

/**
 * Control configuration of RequireJS
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage JavaScript
 */
class Elgg_Amd_Config {
	private $baseUrl = '';
	private $paths = array();
	private $shim = array();
	private $dependencies = array();

	/**
	 * Set the base URL for the site
	 *
	 * @param string $url URL
	 * @return void
	 */
	public function setBaseUrl($url) {
		$this->baseUrl = $url;
	}

	/**
	 * Add a path mapping for a module. If a path is already defined, sets
	 * current path as preferred.
	 *
	 * @param string $name Module name
	 * @param string $path Full URL of the module
	 * @return void
	 */
	public function addPath($name, $path) {
		if (preg_match("/\.js$/", $path)) {
			$path = preg_replace("/\.js$/", '', $path);
		}

		if (!isset($this->paths[$name])) {
			$this->paths[$name] = array();
		}

		array_unshift($this->paths[$name], $path);
	}

	/**
	 * Remove a path for a module
	 *
	 * @param string $name Module name
	 * @param mixed  $path The path to remove. If null, removes all paths (default).
	 * @return void
	 */
	public function removePath($name, $path = null) {
		if (!$path) {
			unset($this->paths[$name]);
		}

		$key = array_search($path, $this->paths[$name]);
		unset($this->paths[$name][$key]);
	}

	/**
	 * Configures a shimmed module
	 *
	 * @param string $name   Module name
	 * @param array  $config Configuration for the module
	 *                           deps:     array  Dependencies
	 *                           exports:  string Name of the shimmed module to export
	 * @return void
	 */
	public function addShim($name, array $config) {
		$deps = elgg_extract('deps', $config, array());
		$exports = elgg_extract('exports', $config);

		if (empty($deps) && empty($exports)) {
			throw new InvalidParameterException("Shimmed modules must have deps or exports");
		}

		$this->shim[$name] = array();

		if (!empty($deps)) {
			$this->shim[$name]['deps'] = $deps;
		}

		if (!empty($exports)) {
			$this->shim[$name]['exports'] = $exports;
		}
	}

	/**
	 * Is this shim defined
	 *
	 * @param string $name The name of the shim
	 * @return bool
	 */
	public function hasShim($name) {
		return isset($this->shim[$name]);
	}

	/**
	 * Unregister the shim config for a module
	 *
	 * @param string $name Module name
	 * @return void
	 */
	public function removeShim($name) {
		unset($this->shim[$name]);
	}

	/**
	 * Add a dependency
	 *
	 * @param string $name Name of the dependency
	 * @return void
	 */
	public function addDependency($name) {
		$this->dependencies[$name] = true;
	}

	/**
	 * Removes a dependency
	 *
	 * @param string $name Name of the dependency
	 * @return void
	 */
	public function removeDependency($name) {
		unset($this->dependencies[$name]);
	}

	/**
	 * Get registered dependencies
	 *
	 * @return array
	 */
	public function getDependencies() {
		return array_keys($this->dependencies);
	}

	/**
	 * Is this dependency registered
	 *
	 * @param string $name Module name
	 * @return bool
	 */
	public function hasDependency($name) {
		return isset($this->dependencies[$name]);
	}

	/**
	 * Adds a standard AMD or shimmed module to the config.
	 *
	 * @param string $name   The name of the module
	 * @param array  $config Configuration for the module
	 *                           url:      string The full URL for the module if not resolvable from baseUrl
	 *                           deps:     array  Shimmed module's dependencies
	 *                           exports:  string Name of the shimmed module to export
	 *
	 * @return void
	 */
	public function addModule($name, array $config = array()) {
		$url = elgg_extract('url', $config);
		$deps = elgg_extract('deps', $config, array());
		$exports = elgg_extract('exports', $config);

		if (!empty($url)) {
			$this->addPath($name, $url);
		}

		// this is a shimmed module
		// some jQuery modules don't need to export anything when shimmed,
		// so check for deps too
		if (!empty($deps) || !empty($exports)) {
			$this->addShim($name, $config);
		} else {
			$this->addDependency($name);
		}
	}

	/**
	 * Removes all config for a module
	 *
	 * @param type $name The module name
	 * @return bool
	 */
	public function removeModule($name) {
		_elgg_services()->amdConfig->removeDependency($name);
		_elgg_services()->amdConfig->removeShim($name);
		_elgg_services()->amdConfig->removePath($name);
	}

	/**
	 * Is module configured?
	 *
	 * @param string $name Module name
	 * @return boolean
	 */
	public function hasModule($name) {
		if (in_array($name, $this->getDependencies())) {
			return true;
		}

		if (isset($this->shims[$name])) {
			return true;
		}

		if (isset($this->paths[$name])) {
			return true;
		}

		return false;
	}

	/**
	 * Get the configuration of AMD
	 *
	 * @return array
	 */
	public function getConfig() {
		return array(
			'baseUrl' => $this->baseUrl,
			'paths' => $this->paths,
			'shim' => $this->shim,
			'deps' => $this->getDependencies(),
		);
	}
}