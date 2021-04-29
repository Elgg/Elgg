<?php
/**
 * Upgrade object for upgrades that need to be tracked
 * and listed in the admin area.
 */

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Traits\TimeUsing;
use Elgg\Upgrade\Batch;

/**
 * Represents an upgrade that runs outside of the upgrade.php script.
 *
 * @internal
 *
 * @property      bool   $is_completed   Is the upgrade completed yet
 * @property      int    $processed      Number of items processed
 * @property      int    $offset         Offset for batch
 * @property      int    $has_errors     Number of errors
 * @property      int    $completed_time Time when the upgrade finished
 * @property      int    $start_time     Time when the upgrade started
 * @property-read string $id             The ID of the upgrade
 * @property-read string $class          The class which will handle the upgrade
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
	}

	/**
	 * Mark this upgrade as completed
	 *
	 * @return void
	 */
	public function setCompleted() {
		$this->setStartTime(); // to make sure a start time is present
		$this->setCompletedTime();
		$this->is_completed = true;

		elgg_trigger_event('complete', 'upgrade', $this);
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
	 * Check if the upgrade should be run asynchronously
	 * @return bool
	 */
	public function isAsynchronous() {
		return !is_subclass_of($this->class, \Elgg\Upgrade\SystemUpgrade::class);
	}

	/**
	 * Return instance of the class that processes the data
	 *
	 * @return Batch|false
	 */
	public function getBatch() {
		try {
			$batch = _elgg_services()->upgradeLocator->getBatch($this->class);
		} catch (ElggInvalidArgumentException $ex) {
			// only report error if the upgrade still needs to run
			$loglevel = $this->isCompleted() ? 'INFO' : 'ERROR';
			elgg_log($ex->getMessage(), $loglevel);
			
			return false;
		}

		// check version before shouldBeSkipped() so authors can get immediate feedback on an invalid batch.
		$version = $batch->getVersion();

		// Version must be in format yyyymmddnn
		if (preg_match("/^[0-9]{10}$/", $version) == 0) {
			elgg_log("Upgrade $this->class returned an invalid version: $version");
			return false;
		}

		return $batch;
	}

	/**
	 * Sets the timestamp for when the upgrade completed.
	 *
	 * @param int $time Timestamp when upgrade finished. Defaults to now
	 *
	 * @return int
	 */
	public function setCompletedTime($time = null) {
		if (!is_int($time)) {
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
	 * Resets the update in order to be able to run it again
	 *
	 * @return void
	 */
	public function reset() {
		unset($this->is_completed);
		unset($this->completed_time);
		unset($this->processed);
		unset($this->offset);
		unset($this->start_time);
	}
	
	/**
	 * Sets the timestamp for when the upgrade started.
	 * Once set it can't be altered unless the upgrade gets reset
	 *
	 * @param int $time Timestamp when upgrade started. Defaults to now
	 *
	 * @return int
	 */
	public function setStartTime($time = null) {
		if (!is_int($time)) {
			$time = $this->getCurrentTime()->getTimestamp();
		}
		
		if (isset($this->start_time)) {
			return $this->start_time;
		}
		
		return $this->start_time = $time;
	}
	
	/**
	 * Gets the time when the upgrade completed.
	 *
	 * @return int
	 */
	public function getStartTime() {
		return (int) $this->start_time;
	}

	/**
	 * {@inheritDoc}
	 * @throws UnexpectedValueException
	 */
	public function save() : bool {
		if (!isset($this->is_completed)) {
			$this->is_completed = false;
		}

		foreach ($this->requiredProperties as $prop) {
			if (!$this->$prop) {
				throw new UnexpectedValueException("ElggUpgrade objects must have a value for the {$prop} property.");
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
	 * {@inheritDoc}
	 * @see ElggData::__isset()
	 */
	public function __isset($name) {
		if (array_key_exists($name, $this->attributes)) {
			return parent::__isset($name);
		}
		
		$private_setting = $this->getPrivateSetting($name);
		return !is_null($private_setting);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName() {
		return elgg_echo($this->title);
	}
}
