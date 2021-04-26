<?php

namespace Elgg\Assets;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 * @since  1.10.0
 */
class ExternalFiles {

	/**
	 * @var array
	 */
	protected $files = [];

	/**
	 * Core registration function for external files
	 *
	 * @param string $type     Type of external resource (js or css)
	 * @param string $name     Identifier used as key
	 * @param string $url      URL
	 * @param string $location Location in the page to include the file
	 *
	 * @return bool
	 */
	public function register(string $type, string $name, string $url, string $location): bool {
		$name = trim(strtolower($name));
		if (empty($name) || empty($url)) {
			return false;
		}
	
		$url = elgg_normalize_url($url);

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
	
		return true;
	}
	
	/**
	 * Unregister an external file
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier of the file
	 *
	 * @return bool
	 */
	public function unregister(string $type, string $name): bool {
		$this->setupType($type);
		
		$name = trim(strtolower($name));
	
		if (!isset($this->files[$type][$name])) {
			return false;
		}
		
		unset($this->files[$type][$name]);
		return true;
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
			if (elgg_view_exists($name)) {
				$item->url = elgg_get_simplecache_url($name);
				$item->location = ($type == 'js') ? 'foot' : 'head';
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
	 * @return string[] URLs of files to load
	 */
	public function getLoadedFiles(string $type, string $location): array {
		if (!isset($this->files[$type])) {
			return [];
		}

		$items = $this->files[$type];

		// only return loaded files for this location
		$items = array_filter($items, function($v) use ($location) {
			return $v->loaded == true && $v->location == $location;
		});
		
		// return only urls
		if (!empty($items)) {
			array_walk($items, function(&$v, $k){
				$v = $v->url;
			});
		}
		
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
}
