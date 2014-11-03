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
	function register($type, $name, $url, $location, $priority = 500) {
		global $CONFIG;
	
		if (empty($name) || empty($url)) {
			return false;
		}
	
		$url = elgg_format_url($url);
		$url = elgg_normalize_url($url);
		
		_elgg_bootstrap_externals_data_structure($type);
	
		$name = trim(strtolower($name));
	
		// normalize bogus priorities, but allow empty, null, and false to be defaults.
		if (!is_numeric($priority)) {
			$priority = 500;
		}
	
		// no negative priorities right now.
		$priority = max((int)$priority, 0);
	
		$item = elgg_extract($name, $CONFIG->externals_map[$type]);
	
		if ($item) {
			// updating a registered item
			// don't update loaded because it could already be set
			$item->url = $url;
			$item->location = $location;
	
			// if loaded before registered, that means it hasn't been added to the list yet
			if ($CONFIG->externals[$type]->contains($item)) {
				$priority = $CONFIG->externals[$type]->move($item, $priority);
			} else {
				$priority = $CONFIG->externals[$type]->add($item, $priority);
			}
		} else {
			$item = new \stdClass();
			$item->loaded = false;
			$item->url = $url;
			$item->location = $location;
	
			$priority = $CONFIG->externals[$type]->add($item, $priority);
		}
	
		$CONFIG->externals_map[$type][$name] = $item;
	
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
	function unregister($type, $name) {
		global $CONFIG;
	
		_elgg_bootstrap_externals_data_structure($type);
	
		$name = trim(strtolower($name));
		$item = elgg_extract($name, $CONFIG->externals_map[$type]);
	
		if ($item) {
			unset($CONFIG->externals_map[$type][$name]);
			return $CONFIG->externals[$type]->remove($item);
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
	function load($type, $name) {
		global $CONFIG;
	
		_elgg_bootstrap_externals_data_structure($type);
	
		$name = trim(strtolower($name));
	
		$item = elgg_extract($name, $CONFIG->externals_map[$type]);
	
		if ($item) {
			// update a registered item
			$item->loaded = true;
		} else {
			$item = new \stdClass();
			$item->loaded = true;
			$item->url = '';
			$item->location = '';
	
			$CONFIG->externals[$type]->add($item);
			$CONFIG->externals_map[$type][$name] = $item;
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
	function getLoadedFiles($type, $location) {
		global $CONFIG;
	
		if (isset($CONFIG->externals) && $CONFIG->externals[$type] instanceof \ElggPriorityList) {
			$items = $CONFIG->externals[$type]->getElements();
	
			$callback = "return \$v->loaded == true && \$v->location == '$location';";
			$items = array_filter($items, create_function('$v', $callback));
			if ($items) {
				array_walk($items, create_function('&$v,$k', '$v = $v->url;'));
			}
			return $items;
		}
		return array();
	}
	
	/**
	 * Bootstraps the externals data structure in $CONFIG.
	 *
	 * @param string $type The type of external, js or css.
	 * @access private
	 */
	function bootstrap($type) {
		global $CONFIG;
	
		if (!isset($CONFIG->externals)) {
			$CONFIG->externals = array();
		}
	
		if (!isset($CONFIG->externals[$type]) || !$CONFIG->externals[$type] instanceof \ElggPriorityList) {
			$CONFIG->externals[$type] = new \ElggPriorityList();
		}
	
		if (!isset($CONFIG->externals_map)) {
			$CONFIG->externals_map = array();
		}
	
		if (!isset($CONFIG->externals_map[$type])) {
			$CONFIG->externals_map[$type] = array();
		}
	}
}