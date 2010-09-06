<?php
/**
 * @class ElggPlugin Object representing a plugin's settings for a given site.
 * This class is currently a stub, allowing a plugin to saving settings in an object's metadata for each site.
 * @author Curverider Ltd
 */
class ElggPlugin extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = "plugin";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
	 */
	public function get($name) {
		// See if its in our base attribute
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}

		// No, so see if its in the private data store.
		// get_private_setting() returns false if it doesn't exist
		$meta = get_private_setting($this->guid, $name);

		if ($meta === false) {
			// Can't find it, so return null
			return NULL;
		}

		return $meta;
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name=='guid')) {
				return false;
			}

			$this->attributes[$name] = $value;
		} else {
			return set_private_setting($this->guid, $name, $value);
		}

		return true;
	}
}