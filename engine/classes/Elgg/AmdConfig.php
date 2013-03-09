<?php

/**
 * Control configuration of RequireJS
 *
 * @access private
 */
class Elgg_AmdConfig {
	private $baseUrl = '';
	private $paths = array();
	private $shim = array();
	private $dependencies = array();
	
	
	public function setBaseUrl($url) {
		$this->baseUrl = $url;
	}

	public function setPath($module, $path) {
		if (preg_match("/\.js$/", $path)) {
			$path = preg_replace("/\.js$/", '', $path);
		}
		
		$this->paths[$module] = $path;
	}
	
	public function setShim($module, array $shimConfig) {
		$this->shim[$module] = array(
			'deps' => elgg_extract('deps', $shimConfig, array()),
			'exports' => elgg_extract('exports', $shimConfig),
		);
	}

	public function addDependency($name) {
		$this->dependencies[$name] = true;
	}

	public function getDependencies() {
		return array_keys($this->dependencies);
	}

	public function getConfig() {
		return array(
			'baseUrl' => $this->baseUrl,
			'paths' => $this->paths,
			'shim' => $this->shim,
			'deps' => $this->getDependencies(),
		);
	}
}
