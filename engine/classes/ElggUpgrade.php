<?php
/**
 * Upgrade object for upgrades that need to be tracked
 * and listed in the admin area.
 *
 * @todo Expand for all upgrades to be \ElggUpgrade subclasses.
 */

/**
 * Represents an upgrade that runs outside of the upgrade.php script.
 * These are listed in admin/upgrades and allow for ajax upgrades.
 *
 * @package Elgg.Admin
 */
class ElggUpgrade extends \ElggObject {
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
	 * Sets an upgrade URL
	 *
	 * @param string $url Set the URL for the upgrade page
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function setURL($url) {
		// elgg_normalize_url() returns the root URL if passed an empty string
		
		if (!$url) {
			throw new \InvalidArgumentException(elgg_echo('ElggUpgrade:error:url_invalid'));
		}

		$url = elgg_normalize_url($url);

		if ($this->getUpgradeFromURL($url)) {
			throw new \InvalidArgumentException(elgg_echo('ElggUpgrade:error:url_not_unique'));
		}

		return $this->upgrade_url = $url;
	}

	/**
	 * Returns a normalized URL for the upgrade page.
	 *
	 * @return string
	 */
	public function getURL() {
		return $this->upgrade_url;
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
				throw new \UnexpectedValueException(elgg_echo("ElggUpgrade:error:{$prop}_required"));
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
	 * Find an \ElggUpgrade object by the unique URL
	 *
	 * @param string $url The Upgrade URL
	 * @return \ElggUpgrade|boolean
	 */
	public function getUpgradeFromURL($url) {
		$url = elgg_normalize_url($url);

		if (!$url) {
			return false;
		}
		
		$options = array(
			'type' => 'object',
			'subtype' => 'elgg_upgrade',
			'private_setting_name' => 'upgrade_url',
			'private_setting_value' => $url
		);

		$upgrades = call_user_func($this->_callable_egefps, $options);

		if ($upgrades) {
			return $upgrades[0];
		}

		return false;
	}
}