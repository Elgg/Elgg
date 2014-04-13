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
	 * Set the path for a module
	 *
	 * @todo update documentation
	 * 
	 * @param string $module Module name
	 * @param string $path   Relative filepath? for the module
	 * @return void
	 */
	public function setPath($module, $path) {
		if (preg_match("/\.js$/", $path)) {
			$path = preg_replace("/\.js$/", '', $path);
		}

		// Avoid .js suffixing: http://stackoverflow.com/a/15392880
		$path = "$path?";

		$this->paths[$module] = $path;
	}

	/**
	 * Unset the path for a module
	 *
	 * @param string $module Module name
	 * @return void
	 */
	public function unsetPath($module) {
		unset($this->paths[$module]);
	}

	/**
	 * Sets the shim for a module
	 *
	 * @todo update documentation
	 * 
	 * @param string $module     Module name
	 * @param array  $shimConfig Configuration for the module
	 *                             'deps': dependencies
	 *                             'exports': something else
	 * @return void
	 */
	public function setShim($module, array $shimConfig) {
		$deps = elgg_extract('deps', $shimConfig, array());
		$exports = elgg_extract('exports', $shimConfig);

		if (!empty($deps) || !empty($exports)) {
			$this->shim[$module] = array();
		}
		if (!empty($deps)) {
			$this->shim[$module]['deps'] = $deps;
		}
		if (!empty($exports)) {
			$this->shim[$module]['exports'] = $exports;
		}
	}

	/**
	 * Unset the shim of a module
	 *
	 * @param string $module Module name
	 * @return void
	 */
	public function unsetShim($module) {
		unset($this->shim[$module]);
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
