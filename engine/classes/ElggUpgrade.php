<?php

/**
 * Upgrade object for upgrades that need to be tracked
 * and listed in the admin area.
 *
 * @todo Expand for all upgrades to be \ElggUpgrade subclasses.
 */

use Elgg\TimeUsing;
use Elgg\Upgrade\Batch;

/**
 * Represents an upgrade that runs outside of the upgrade.php script.
 *
 * @package Elgg.Admin
 * @access private
 */
class ElggUpgrade extends ElggObject {

	use TimeUsing;
	
	private $requiredProperties = [
		'id',
		'title',
		'description',
		'class',
	];

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
	 * Sets an unique id for the upgrade
	 *
	 * @param string $id Upgrade id in format <plugin_name>:<yyymmddhh>
	 * @return void
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Sets a class for the upgrade
	 *
	 * @param string $class Fully qualified class name
	 * @return void
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * Return instance of the class that processes the data
	 *
	 * @return Batch|false
	 */
	public function getBatch() {
		return _elgg_services()->upgradeLocator->getBatch($this->class);
	}

	/**
	 * Sets the timestamp for when the upgrade completed.
	 *
	 * @param int $time Timestamp when upgrade finished. Defaults to now.
	 * @return bool
	 */
	public function setCompletedTime($time = null) {
		if (!$time) {
			$time = $this->getCurrentTime()->getTimestamp();
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

}
