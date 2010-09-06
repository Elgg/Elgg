<?php

/**
 * Define an interface for geo-tagging entities.
 *
 */
interface Locatable {
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