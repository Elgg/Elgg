<?php

namespace Elgg\Amd;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * Control configuration of RequireJS
 *
 * @internal
 */
class Config {
	
	private $baseUrl = '';
	
	private $paths = [];
	
	private $shim = [];
	
	private $dependencies = [];

	/**
	 * @var \Elgg\EventsService
	 */
	protected $events;
	
	/**
	 * Constructor
	 *
	 * @param \Elgg\EventsService $events The events service
	 */
	public function __construct(\Elgg\EventsService $events) {
		$this->events = $events;
	}
	
	/**
	 * Set the base URL for the site
	 *
	 * @param string $url URL
	 *
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
	 *
	 * @return void
	 */
	public function addPath(string $name, string $path): void {
		if (preg_match('/\.js$/', $path)) {
			$path = preg_replace('/\.js$/', '', $path);
		}

		if (!isset($this->paths[$name])) {
			$this->paths[$name] = [];
		}

		array_unshift($this->paths[$name], $path);
	}

	/**
	 * Remove a path for a module
	 *
	 * @param string $name Module name
	 * @param mixed  $path The path to remove. If null, removes all paths (default).
	 *
	 * @return void
	 */
	public function removePath($name, $path = null) {
		if (!$path) {
			unset($this->paths[$name]);
		} else {
			if (preg_match('/\.js$/', $path)) {
				$path = preg_replace('/\.js$/', '', $path);
			}

			$key = array_search($path, $this->paths[$name]);
			unset($this->paths[$name][$key]);

			if (empty($this->paths[$name])) {
				unset($this->paths[$name]);
			}
		}
	}

	/**
	 * Configures a shimmed module
	 *
	 * @param string $name   Module name
	 * @param array  $config Configuration for the module
	 *                       - deps:     array  Dependencies
	 *                       - exports:  string Name of the shimmed module to export
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function addShim(string $name, array $config): void {
		$deps = elgg_extract('deps', $config, []);
		$exports = elgg_extract('exports', $config);

		if (empty($deps) && empty($exports)) {
			throw new InvalidArgumentException('Shimmed modules must have deps or exports');
		}

		$this->shim[$name] = [];

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
	 *
	 * @return bool
	 */
	public function hasShim($name) {
		return isset($this->shim[$name]);
	}

	/**
	 * Unregister the shim config for a module
	 *
	 * @param string $name Module name
	 *
	 * @return void
	 */
	public function removeShim($name) {
		unset($this->shim[$name]);
	}

	/**
	 * Add a dependency
	 *
	 * @param string $name Name of the dependency
	 *
	 * @return void
	 */
	public function addDependency(string $name): void {
		$this->dependencies[$name] = true;
	}

	/**
	 * Removes a dependency
	 *
	 * @param string $name Name of the dependency
	 *
	 * @return void
	 */
	public function removeDependency(string $name): void {
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
	 *
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
	 *                       - url:      string The full URL for the module if not resolvable from baseUrl
	 *                       - deps:     array  Shimmed module's dependencies
	 *                       - exports:  string Name of the shimmed module to export
	 *
	 * @return void
	 */
	public function addModule($name, array $config = []) {
		$url = elgg_extract('url', $config);
		$deps = elgg_extract('deps', $config, []);
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
	 * @param string $name The module name
	 *
	 * @return void
	 */
	public function removeModule($name) {
		$this->removeDependency($name);
		$this->removeShim($name);
		$this->removePath($name);
	}

	/**
	 * Is module configured?
	 *
	 * @param string $name Module name
	 *
	 * @return bool
	 */
	public function hasModule($name) {
		if (in_array($name, $this->getDependencies())) {
			return true;
		}

		if (isset($this->shim[$name])) {
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
		$defaults = [
			'baseUrl' => $this->baseUrl,
			'paths' => $this->paths,
			'shim' => $this->shim,
			'deps' => $this->getDependencies(),
			'waitSeconds' => 20,
		];
		
		$params = [
			'defaults' => $defaults
		];
		
		return $this->events->triggerResults('config', 'amd', $params, $defaults);
	}
}
