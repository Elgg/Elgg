<?php

/**
 * Control configuration of RequireJS
 *
 * @access private
 */
class Elgg_AmdConfig {

	protected $dependencies = array();
	protected $base_path;
	protected $viewtype;
	protected $cache_timestamp;

	public function __construct($base_path, $viewtype) {
		$this->base_path = $base_path;
		$this->viewtype = $viewtype;
	}

	public function addDependency($name) {
		$this->dependencies[$name] = true;
	}

	public function getDependencies() {
		return array_keys($this->dependencies);
	}

	public function useSimplecache($cache_timestamp) {
		$this->cache_timestamp = (int)$cache_timestamp;
	}

	public function getConfig() {
		if (null === $this->cache_timestamp) {
			$config = array(
				'baseUrl' => "{$this->base_path}ajax/view/js/",
				'urlArgs' => "view={$this->viewtype}",
			);
		} else {
			$config = array(
				'baseUrl' => "{$this->base_path}cache/{$this->cache_timestamp}/{$this->viewtype}/js/",
			);
		}
		$config['deps'] = $this->getDependencies();
		return $config;
	}
}
