<?php
namespace Elgg\Assets;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Assets
 * @since      1.10.0
 */
class ExternalFiles {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor
	 *
	 * @param \stdClass $config Predefined configuration in format used by global $CONFIG variable
	 */
	public function __construct(\stdClass $config = null) {
		if (!($config instanceof \stdClass)) {
			$config = new \stdClass();
		}
		$this->CONFIG = $config;
	}
	
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
	
		$url = elgg_format_url($url);
		$url = elgg_normalize_url($url);

		$this->bootstrap($type);
	
		$name = trim(strtolower($name));
	
		// normalize bogus priorities, but allow empty, null, and false to be defaults.
		if (!is_numeric($priority)) {
			$priority = 500;
		}
	
		// no negative priorities right now.
		$priority = max((int)$priority, 0);
	
		$item = elgg_extract($name, $this->CONFIG->externals_map[$type]);
	
		if ($item) {
			// updating a registered item
			// don't update loaded because it could already be set
			$item->url = $url;
			$item->location = $location;
	
			// if loaded before registered, that means it hasn't been added to the list yet
			if ($this->CONFIG->externals[$type]->contains($item)) {
				$priority = $this->CONFIG->externals[$type]->move($item, $priority);
			} else {
				$priority = $this->CONFIG->externals[$type]->add($item, $priority);
			}
		} else {
			$item = new \stdClass();
			$item->loaded = false;
			$item->url = $url;
			$item->location = $location;
	
			$priority = $this->CONFIG->externals[$type]->add($item, $priority);
		}
	
		$this->CONFIG->externals_map[$type][$name] = $item;
	
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

		$this->bootstrap($type);
	
		$name = trim(strtolower($name));
		$item = elgg_extract($name, $this->CONFIG->externals_map[$type]);
	
		if ($item) {
			unset($this->CONFIG->externals_map[$type][$name]);
			return $this->CONFIG->externals[$type]->remove($item);
		}
	
		return false;
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
		
	
		$this->bootstrap($type);
	
		$name = trim(strtolower($name));
	
		$item = elgg_extract($name, $this->CONFIG->externals_map[$type]);
	
		if ($item) {
			// update a registered item
			$item->loaded = true;
		} else {
			$item = new \stdClass();
			$item->loaded = true;
			$item->url = '';
			$item->location = '';
	
			$this->CONFIG->externals[$type]->add($item);
			$this->CONFIG->externals_map[$type][$name] = $item;
		}
	}
	
	/**
	 * Get external resource descriptors
	 *
	 * @param string $type     Type of file: js or css
	 * @param string $location Page location
	 *
	 * @return array
	 */
	public function getLoadedFiles($type, $location) {
		
	
		if (
			isset($this->CONFIG->externals)
			&& isset($this->CONFIG->externals[$type])
			&& $this->CONFIG->externals[$type] instanceof \ElggPriorityList
		) {
			$items = $this->CONFIG->externals[$type]->getElements();
	
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
		return array();
	}
	
	/**
	 * Bootstraps the externals data structure in $CONFIG.
	 *
	 * @param string $type The type of external, js or css.
	 * @return null
	 */
	protected function bootstrap($type) {

		if (!isset($this->CONFIG->externals)) {
			$this->CONFIG->externals = array();
		}
	
		if (!isset($this->CONFIG->externals[$type]) || !$this->CONFIG->externals[$type] instanceof \ElggPriorityList) {
			$this->CONFIG->externals[$type] = new \ElggPriorityList();
		}
	
		if (!isset($this->CONFIG->externals_map)) {
			$this->CONFIG->externals_map = array();
		}
	
		if (!isset($this->CONFIG->externals_map[$type])) {
			$this->CONFIG->externals_map[$type] = array();
		}
	}
}