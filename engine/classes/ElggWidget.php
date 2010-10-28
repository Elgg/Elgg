<?php

/**
 * Override ElggObject in order to store widget data in ultra-private stores.
 *
 * @package    Elgg.Core
 * @subpackage Widgets
 */
class ElggWidget extends ElggObject {

	/**
	 * Set subtype to widget.
	 *
	 * @deprecated 1.8 use ElggWidget::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initialise_attributes() {
		elgg_deprecated_notice('ElggWidget::initialise_attributes() is deprecated by ::initializeAttributes()', 1.8);

		return $this->initializeAttributes();
	}

	/**
	 * Set subtype to widget.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "widget";
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
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
		$meta = get_private_setting($this->guid, $name);
		if ($meta) {
			return $meta;
		}

		// Can't find it, so return null
		return null;
	}

	/**
	 * Override entity get and sets in order to save data to private data store.
	 *
	 * @param string $name  Name
	 * @param string $value Value
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