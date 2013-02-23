<?php

/**
 * Control configuration of RequireJS
 *
 * @access private
 */
class Elgg_AmdConfig {
	private $baseUrl = '';
	private $dependencies = array();

	public function setBaseUrl($url) {
		$this->baseUrl = $url;
	}

	public function addDependency($name) {
		$this->dependencies[$name] = true;
	}

	public function getDependencies() {
		return array_keys($this->dependencies);
	}

	public function getConfig() {
		$config = array(
			'baseUrl' => $this->baseUrl,
			'deps' => $this->getDependencies(),
		);
		
		return $config;
	}
}
