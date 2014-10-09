<?php
/**
 * Upgrade object for upgrades that need to be tracked
 * and listed in the admin area.
 *
 * @todo Expand for all upgrades to be ElggUpgrade subclasses.
 */

/**
 * Represents an upgrade that runs outside of the upgrade.php script.
 * These are listed in admin/upgrades and allow for ajax upgrades.
 *
 * @note The "upgrade_url" private setting originally stored the full URL, but
 *       was changed to hold the relative path from the site URL for #6838
 *
 * @package Elgg.Admin
 * @access private
 */
class ElggUpgrade extends ElggObject {
	private $requiredProperties = array(
		'title',
		'description',
		'upgrade_url',
	);

	/**
	 * Do not use.
	 *
	 * @access private
	 * @var callable
	 */
	public $_callable_egefps = 'elgg_get_entities_from_private_settings';

	/**
	 * Set subtype to upgrade
	 *
	 * @return null
	 */
	public function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'elgg_upgrade';

		// unowned
		$this->attributes['site_guid'] = 0;
		$this->attributes['container_guid'] = 0;
		$this->attributes['owner_guid'] = 0;

		$this->is_completed = 0;
	}

	/**
	 * Mark this upgrade as completed
	 *
	 * @return bool
	 */
	public function setCompleted() {
		$this->setCompletedTime();
		return $this->is_completed = true;
	}

	/**
	 * Has this upgrade completed?
	 *
	 * @return bool
	 */
	public function isCompleted() {
		return (bool) $this->is_completed;
	}

	/**
	 * Sets an upgrade URL path
	 *
	 * @param string $path Set the URL path (without site URL) for the upgrade page
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function setPath($path) {
		if (!$path) {
			throw new InvalidArgumentException('Invalid value for URL path.');
		}

		$path = ltrim($path, '/');

		if ($this->getUpgradeFromPath($path)) {
			throw new InvalidArgumentException('Upgrade URL paths must be unique.');
		}

		$this->upgrade_url = $path;
	}

	/**
	 * Returns a normalized URL for the upgrade page.
	 *
	 * @return string
	 */
	public function getURL() {
		return elgg_normalize_url($this->upgrade_url);
	}

	/**
	 * Sets the timestamp for when the upgrade completed.
	 *
	 * @param int $time Timestamp when upgrade finished. Defaults to now.
	 * @return bool
	 */
	public function setCompletedTime($time = null) {
		if (!$time) {
			$time = time();
		}

		return $this->completed_time = $time;
	}

	/**
	 * Gets the time when the upgrade completed.
	 *
	 * @return string
	 */
	public function getCompletedTime() {
		return $this->completed_time;
	}

	/**
	 * Require an upgrade page.
	 *
	 * @return mixed
	 * @throws UnexpectedValueException
	 */
	public function save() {
		foreach ($this->requiredProperties as $prop) {
			if (!$this->$prop) {
				throw new UnexpectedValueException("ElggUpgrade objects must have a value for the $prop property.");
			}
		}

		return parent::save();
	}

	/**
	 * Set a value as private setting or attribute.
	 *
	 * Attributes include title and description.
	 *
	 * @param string $name  Name of the attribute or private_setting
	 * @param mixed  $value Value to be set
	 * @return void
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			parent::__set($name, $value);
		} else {
			$this->setPrivateSetting($name, $value);
		}
	}

	/**
	 * Get an attribute or private setting value
	 *
	 * @param string $name Name of the attribute or private setting
	 * @return mixed
	 */
	public function __get($name) {
		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return parent::__get($name);
		}

		return $this->getPrivateSetting($name);
	}

	/**
	 * Find an ElggUpgrade object by the unique URL path
	 *
	 * @param string $path The Upgrade URL path (after site URL)
	 * @return ElggUpgrade|false
	 */
	public function getUpgradeFromPath($path) {
		$path = ltrim($path, '/');

		if (!$path) {
			return false;
		}

		// test for full URL values (used at 1.9.0)
		$options = array(
			'type' => 'object',
			'subtype' => 'elgg_upgrade',
			'private_setting_name' => 'upgrade_url',
			'private_setting_value' => elgg_normalize_url($path),
		);
		$upgrades = call_user_func($this->_callable_egefps, $options);
		/* @var ElggUpgrade[] $upgrades */

		if ($upgrades) {
			// replace URL with path (we can't use setPath due to recursion)
			$upgrades[0]->upgrade_url = $path;
			return $upgrades[0];
		}

		$options['private_setting_value'] = $path;
		$upgrades = call_user_func($this->_callable_egefps, $options);

		if ($upgrades) {
			return $upgrades[0];
		}

		return false;
	}
}