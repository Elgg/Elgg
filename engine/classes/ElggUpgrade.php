<?php
/**
 * Upgrade object for upgrades that need to be tracked
 * and listed in the admin area.
 */

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Exceptions\UnexpectedValueException as ElggUnexpectedValueException;
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
	public function setCompleted(): void {
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
	public function isCompleted(): bool {
		return (bool) $this->is_completed;
	}

	/**
	 * Sets an unique id for the upgrade
	 *
	 * @param string $id Upgrade id in format <plugin_name>:<yyymmddhh>
	 * @return void
	 */
	public function setID(string $id): void {
		$this->id = $id;
	}

	/**
	 * Sets a class for the upgrade
	 *
	 * @param string $class Fully qualified class name
	 * @return void
	 */
	public function setClass(string $class): void {
		$this->class = $class;
	}

	/**
	 * Check if the upgrade should be run asynchronously
	 * @return bool
	 */
	public function isAsynchronous(): bool {
		return !is_subclass_of($this->class, \Elgg\Upgrade\SystemUpgrade::class);
	}

	/**
	 * Return instance of the class that processes the data
	 *
	 * @return Batch|false
	 */
	public function getBatch(): Batch|false {
		try {
			$batch = _elgg_services()->upgradeLocator->getBatch($this->class, $this);
		} catch (ElggInvalidArgumentException $ex) {
			// only report error if the upgrade still needs to run
			$loglevel = $this->isCompleted() ? 'INFO' : 'ERROR';
			elgg_log($ex->getMessage(), $loglevel);
			
			return false;
		}

		// check version before shouldBeSkipped() so authors can get immediate feedback on an invalid batch.
		$version = $batch->getVersion();

		// Version must be in format yyyymmddnn
		if (preg_match('/^[0-9]{10}$/', $version) === 0) {
			elgg_log("Upgrade {$this->class} returned an invalid version: {$version}");
			return false;
		}

		return $batch;
	}

	/**
	 * Sets the timestamp for when the upgrade completed.
	 *
	 * @param int $time Timestamp when upgrade finished. Defaults to now
	 *
	 * @return void
	 */
	public function setCompletedTime(int $time = null): void {
		$this->completed_time = $time ?? $this->getCurrentTime()->getTimestamp();
	}

	/**
	 * Gets the time when the upgrade completed.
	 *
	 * @return int
	 */
	public function getCompletedTime(): int {
		return (int) $this->completed_time;
	}
	
	/**
	 * Resets the update in order to be able to run it again
	 *
	 * @return void
	 */
	public function reset(): void {
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
	 * @return void
	 */
	public function setStartTime(int $time = null): void {
		if (isset($this->start_time)) {
			return;
		}
		
		$this->start_time = $time ?? $this->getCurrentTime()->getTimestamp();
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
	 * @throws \Elgg\Exceptions\UnexpectedValueException
	 */
	public function save(): bool {
		if (!isset($this->is_completed)) {
			$this->is_completed = false;
		}

		foreach ($this->requiredProperties as $prop) {
			if (!$this->$prop) {
				throw new ElggUnexpectedValueException("ElggUpgrade objects must have a value for the {$prop} property.");
			}
		}

		return parent::save();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName(): string {
		return elgg_echo($this->title);
	}
}
