<?php
namespace Elgg\Assets;

use ElggPriorityList;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * @since  1.10.0
 */
class ExternalFiles {

	/**
	 * @var ElggPriorityList[]
	 */
	protected $externals = [];

	/**
	 * @var array
	 */
	protected $externals_map = [];

	/**
	 * Core registration function for external files
	 *
	 * @param string $type     Type of external resource (js or css)
	 * @param string $name     Identifier used as key
	 * @param string $url      URL
	 * @param string $location Location in the page to include the file
	 * @param int    $priority Loading priority of the file
	 *
	 * @return bool
	 */
	public function register($type, $name, $url, $location, $priority = 500) {
		if (empty($name) || empty($url)) {
			return false;
		}
	
		$url = elgg_normalize_url($url);

		$this->setupType($type);
	
		$name = trim(strtolower($name));
	
		// normalize bogus priorities, but allow empty, null, and false to be defaults.
		if (!is_numeric($priority)) {
			$priority = 500;
		}
	
		// no negative priorities right now.
		$priority = max((int) $priority, 0);
	
		$item = elgg_extract($name, $this->externals_map[$type]);
	
		if ($item) {
			// updating a registered item
			// don't update loaded because it could already be set
			$item->url = $url;
			$item->location = $location;
	
			// if loaded before registered, that means it hasn't been added to the list yet
			if ($this->externals[$type]->contains($item)) {
				$priority = $this->externals[$type]->move($item, $priority);
			} else {
				$priority = $this->externals[$type]->add($item, $priority);
			}
		} else {
			$item = (object) [
				'loaded' => false,
				'url' => $url,
				'location' => $location,
			];
			$priority = $this->externals[$type]->add($item, $priority);
		}

		$this->externals_map[$type][$name] = $item;
	
		return $priority !== false;
	}
	
	/**
	 * Unregister an external file
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier of the file
	 *
	 * @return bool
	 */
	public function unregister($type, $name) {
		$this->setupType($type);
	
		$name = trim(strtolower($name));
		$item = elgg_extract($name, $this->externals_map[$type]);
	
		if ($item) {
			unset($this->externals_map[$type][$name]);
			return $this->externals[$type]->remove($item);
		}
	
		return false;
	}

	/**
	 * Get metadata for a registered file
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier of the file
	 *
	 * @return \stdClass|null
	 */
	public function getFile($type, $name) {
		$this->setupType($type);

		$name = trim(strtolower($name));
		if (!isset($this->externals_map[$type][$name])) {
			return null;
		}

		$item = $this->externals_map[$type][$name];
		$priority = $this->externals[$type]->getPriority($item);

		// don't allow internal properties to be altered
		$clone = clone $item;
		$clone->priority = $priority;

		return $clone;
	}
	
	/**
	 * Load an external resource for use on this page
	 *
	 * @param string $type Type of file: js or css
	 * @param string $name The identifier for the file
	 *
	 * @return void
	 */
	public function load($type, $name) {
		$this->setupType($type);
	
		$name = trim(strtolower($name));
	
		$item = elgg_extract($name, $this->externals_map[$type]);
	
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

			$this->externals[$type]->add($item);
			$this->externals_map[$type][$name] = $item;
		}
	}
	
	/**
	 * Get external resource descriptors
	 *
	 * @param string $type     Type of file: js or css
	 * @param string $location Page location
	 *
	 * @return string[] URLs of files to load
	 */
	public function getLoadedFiles($type, $location) {
		if (!isset($this->externals[$type])) {
			return [];
		}

		$items = $this->externals[$type]->getElements();

		$items = array_filter($items, function($v) use ($location) {
			return $v->loaded == true && $v->location == $location;
		});
		if ($items) {
			array_walk($items, function(&$v, $k){
				$v = $v->url;
			});
		}
		return $items;
	}

	/**
	 * Get registered file objects
	 *
	 * @param string $type     Type of file: js or css
	 * @param string $location Page location
	 *
	 * @return \stdClass[]
	 */
	public function getRegisteredFiles($type, $location) {
		if (!isset($this->externals[$type])) {
			return [];
		}

		$ret = [];
		$items = $this->externals[$type]->getElements();
		$items = array_filter($items, function($v) use ($location) {
			return ($v->location == $location);
		});

		foreach ($items as $item) {
			$ret[] = clone $item;
		}

		return $ret;
	}

	/**
	 * Unregister all files
	 *
	 * @return void
	 */
	public function reset() {
		$this->externals = [];
		$this->externals_map = [];
	}
	
	/**
	 * Bootstraps the externals data structure
	 *
	 * @param string $type The type of external, js or css.
	 * @return void
	 */
	protected function setupType($type) {
		if (!isset($this->externals[$type])) {
			$this->externals[$type] = new \ElggPriorityList();
		}
	
		if (!isset($this->externals_map[$type])) {
			$this->externals_map[$type] = [];
		}
	}
}
