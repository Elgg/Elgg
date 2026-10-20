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
 * Represents an upgrade that runs outside the upgrade.php script
 *
 * @internal
 *
 * @property bool   $is_completed   Is the upgrade completed yet
 * @property int    $processed      Number of items processed
 * @property int    $offset         Offset for batch
 * @property int    $has_errors     Number of errors
 * @property int    $completed_time Time when the upgrade finished
 * @property int    $start_time     Time when the upgrade started
 * @property string $id             The ID of the upgrade
 * @property string $class          The class which will handle the upgrade
 */
class ElggUpgrade extends ElggObject {

	use TimeUsing;

	/**
	 * {@inheritdoc}
	 */
	public function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['subtype'] = 'elgg_upgrade';
		$this->attributes['container_guid'] = $site->guid;
		$this->attributes['owner_guid'] = $site->guid;
		
		$this->offset = 0;
		$this->is_completed = false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName(): string {
		$component = $this->getComponent();
		$version = $this->getVersion();
		
		return elgg_echo("{$component}:upgrade:{$version}:title");
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
	 * Get the unique ID for the upgrade in the format <plugin_name>:<yyymmddhh>
	 *
	 * @return string|null
	 * @since 7.1
	 */
	public function getID(): ?string {
		return $this->id;
	}
	
	/**
	 * Get the component where the upgrade originated ('core' or a plugin ID)
	 *
	 * @return string|null
	 */
	public function getComponent(): ?string {
		if (!isset($this->id)) {
			return null;
		}
		
		$parts = explode(':', $this->id);
		
		return elgg_extract(0, $parts);
	}
	
	/**
	 * Get the version of the upgrade in the format <yyymmddhh>
	 *
	 * @return int|null
	 */
	public function getVersion(): ?int {
		if (!isset($this->id)) {
			return null;
		}
		
		$parts = explode(':', $this->id);
		
		return elgg_extract(1, $parts) ? (int) elgg_extract(1, $parts) : null;
	}

	/**
	 * Check if the upgrade should be run asynchronously
	 *
	 * @return bool
	 */
	public function isAsynchronous(): bool {
		return is_subclass_of($this->class, \Elgg\Upgrade\AsynchronousUpgrade::class);
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
			$loglevel = $this->isCompleted() ? \Psr\Log\LogLevel::INFO : \Psr\Log\LogLevel::ERROR;
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
	 * @param null|int $time Timestamp when upgrade finished. Defaults to now
	 *
	 * @return void
	 */
	public function setCompletedTime(?int $time = null): void {
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
		$this->is_completed = false;
		$this->offset = 0;
		
		unset($this->completed_time);
		unset($this->processed);
		unset($this->start_time);
	}
	
	/**
	 * Sets the timestamp for when the upgrade started.
	 * Once set it can't be altered unless the upgrade gets reset
	 *
	 * @param null|int $time Timestamp when upgrade started. Defaults to now
	 *
	 * @return void
	 */
	public function setStartTime(?int $time = null): void {
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
	public function getStartTime(): int {
		return (int) $this->start_time;
	}
}
