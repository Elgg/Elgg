<?php

	/**
	 * Elgg geo-location tagging library.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Define an interface for geo-tagging entities.
	 *
	 */
	interface Locatable
	{
		/** Set a location text */
		public function setLocation($location);
		
		/**
		 * Set latitude and longitude tags for a given entity.
		 *
		 * @param float $lat
		 * @param float $long
		 */
		public function setLatLong($lat, $long);
		
		/**
		 * Get the contents of the ->geo:lat field.
		 *
		 */
		public function getLatitude();
		
		/**
		 * Get the contents of the ->geo:lat field.
		 *
		 */
		public function getLongitude();
		
		/**
		 * Get the ->location metadata. 
		 *
		 */
		public function getLocation();
	}

	/**
	 * Encode a location into a latitude and longitude, caching the result.
	 *
	 * Works by triggering the 'geocode' 'location' plugin hook, and requires a geocoding module to be installed
	 * activated in order to work.
	 * 
	 * @param String $location The location, e.g. "London", or "24 Foobar Street, Gotham City"
	 */
	function elgg_geocode_location($location)
	{
		global $CONFIG;
		
		$location = sanitise_string($location);
		
		// Look for cached version
		$cached_location = get_data_row("SELECT * from {$CONFIG->dbprefix}geocode_cache WHERE location='$location'");
		
		// Trigger geocode event
		$return = false;
		$return = trigger_plugin_hook('geocode', 'location', array('location' => $location, $return));
		
		// If returned, cache and return value
		if (($return) && (is_array($return)))
		{
			$lat = (int)$return['lat'];
			$long = (int)$return['long'];
			
			// Put into cache at the end of the page since we don't really care that much
			execute_delayed_write_query("INSERT DELAYED INTO {$CONFIG->dbprefix}geocode_cache (lat, long) VALUES ({$lat}, {$long}) ON DUPLICATE KEY UPDATE lat={$lat} long={$long}");
		}
		
		return $return;
	}
	
	// Some distances in degrees (approximate)
	define("MILE", 0.01515);
	define("KILOMETER", 0.00932);
	
	
	// TODO: get objects within x miles by entities, metadata and relationship
	
	// TODO: List 
	
	
?>