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

		$this->paths[$module] = $path;
	}

	/**
	 * Method that does something
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
		$this->shim[$module] = array(
			'deps' => elgg_extract('deps', $shimConfig, array()),
			'exports' => elgg_extract('exports', $shimConfig),
		);
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
