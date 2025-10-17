<?php

namespace Elgg\Assets;

use Elgg\Cache\ServerCache;
use Elgg\Cache\SimpleCache;
use Elgg\Config;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\Urls;
use Elgg\ViewsService;

/**
 * External files service
 *
 * @internal
 * @since 1.10.0
 */
class ExternalFiles {

	protected array $files = [];

	/**
	 * Subresource integrity data (loaded on first use)
	 *
	 * @var array
	 */
	protected $sri;
	
	/**
	 * Constructor
	 *
	 * @param Config       $config      config
	 * @param Urls         $urls        urls service
	 * @param ViewsService $views       views service
	 * @param SimpleCache  $simpleCache simplecache
	 * @param ServerCache  $serverCache server cache
	 */
	public function __construct(
		protected Config $config,
		protected Urls $urls,
		protected ViewsService $views,
		protected SimpleCache $simpleCache,
		protected ServerCache $serverCache
	) {
	}

	/**
	 * Core registration function for external files
	 *
	 * @param string $type     Type of external resource (js or css)
	 * @param string $name     Identifier used as key
	 * @param string $url      URL
	 * @param string $location Location in the page to include the file
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function register(string $type, string $name, string $url, string $location): void {
		$name = trim(strtolower($name));
		if (empty($name) || empty($url)) {
			throw new InvalidArgumentException('$name and $url are not allowed to be empty');
		}
	
		$url = $this->urls->normalizeUrl($url);

		$this->setupType($type);
	
		$item = elgg_extract($name, $this->files[$type]);
	
		if ($item) {
			// updating a registered item
			// don't update loaded because it could already be set
			$item->url = $url;
			$item->location = $location;
		} else {
			$item = (object) [
				'loaded' => false,
				'url' => $url,
				'location' => $location,
			];
		}

		$this->files[$type][$name] = $item;
	}
	
	/**
	 * Unregister an external file
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier of the file
	 *
	 * @return void
	 */
	public function unregister(string $type, string $name): void {
		$this->setupType($type);
		
		$name = trim(strtolower($name));

		unset($this->files[$type][$name]);
	}

	/**
	 * Load an external resource for use on this page
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier for the file
	 *
	 * @return void
	 */
	public function load(string $type, string $name): void {
		$this->setupType($type);
	
		$name = trim(strtolower($name));
	
		$item = elgg_extract($name, $this->files[$type]);
	
		if ($item) {
			// update a registered item
			$item->loaded = true;
		} else {
			$item = (object) [
				'loaded' => true,
				'url' => '',
				'location' => '',
			];
			if ($this->views->viewExists($name)) {
				$item->url = $this->simpleCache->getUrl($name);
				$item->location = ($type === 'js') ? 'footer' : 'head';
			}
		}
		
		$this->files[$type][$name] = $item;
	}
	
	/**
	 * Get external resource descriptors
	 *
	 * @param string $type     Type of file: js or css
	 * @param string $location Page location
	 *
	 * @return string[] Resources to load
	 */
	public function getLoadedResources(string $type, string $location): array {
		if (!isset($this->files[$type])) {
			return [];
		}

		$items = $this->files[$type];

		// only return loaded files for this location
		$items = array_filter($items, function($v) use ($location) {
			return $v->loaded == true && $v->location == $location;
		});
		
		$cache_ts = $this->config->lastcache;
		$cache_url = $this->config->wwwroot . "cache/{$cache_ts}/default/";
		
		// check if SRI data is available
		array_walk($items, function(&$v, $k) use ($type, $cache_url) {
			$view = str_replace($cache_url, '', $v->url);
			$v->integrity = $this->getSubResourceIntegrity($type, $view);
		});
		
		return $items;
	}

	/**
	 * Unregister all files
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->files = [];
	}
	
	/**
	 * Bootstraps the externals data structure
	 *
	 * @param string $type The type of external, js or css.
	 * @return void
	 */
	protected function setupType(string $type): void {
		if (!isset($this->files[$type])) {
			$this->files[$type] = [];
		}
	}
	
	/**
	 * Returns the integrity related to the resource file
	 *
	 * @param string $type     type of resource
	 * @param string $resource name of resource
	 * @return string|null
	 */
	public function getSubResourceIntegrity(string $type, string $resource): ?string {
		if (!$this->config->subresource_integrity_enabled) {
			return null;
		}
		
		if (!isset($this->sri)) {
			$this->sri = $this->serverCache->load('sri') ?? [];
		}
		
		return $this->sri[$type][$resource] ?? null;
	}
}
