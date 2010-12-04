<?php
/**
 * Stores site-side plugin settings as private data.
 *
 * This class is currently a stub, allowing a plugin to
 * save settings in an object's private settings for each site.
 *
 * @package    Elgg.Core
 * @subpackage Plugins.Settings
 */
class ElggPlugin extends ElggObject {

	/**
	 * Set subtype to 'plugin'
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "plugin";
	}


	/**
	 * Get a value from private settings.
	 *
	 * @param string $name Name
	 *
	 * @return mixed
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
	 * Save a value to private settings.
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name == 'guid')) {
				return false;
			}

			$this->attributes[$name] = $value;
		} else {
			return set_private_setting($this->guid, $name, $value);
		}

		return true;
	}
}