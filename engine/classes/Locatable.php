<?php

/**
 * Define an interface for geo-tagging entities.
 *
 * @package    Elgg.Core
 * @subpackage SocialModel.Locatable
 */
interface Locatable {
	/**
	 * Set a location text
	 *
	 * @param string $location Textual representation of location
	 *
	 * @return void
	 */
	public function setLocation($location);

	/**
	 * Set latitude and longitude tags for a given entity.
	 *
	 * @param float $lat  Latitude
	 * @param float $long Longitude
	 *
	 * @return void
	 */
	public function setLatLong($lat, $long);

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 * @return int
	 */
	public function getLatitude();

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 * @return int
	 */
	public function getLongitude();

	/**
	 * Get the ->location metadata.
	 *
	 * @return string
	 */
	public function getLocation();
}
