<?php

namespace Elgg\Upgrades;

/**
 * Represents a simple upgrade
 *
 * This should be used for quick and simple upgrades like:
 *  - Adding a new database column
 *  - Saving a default value for a setting introduced in the new version
 *
 * @since 2.0.0
 */
interface Upgrade {
	/**
	 * Get the user facing title of the upgrade
	 *
	 * @return string
	 */
	public function getTitle();

	/**
	 * Get the user facing description of the upgrade
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Does the system need this upgrade in order to function properly
	 *
	 * @return boolean
	 */
	public function isRequired();

	/**
	 * Run the upgrade
	 */
	public function run();

	/**
	 * Version of the upgrade
	 *
	 * The version in format YYYYMMDDXX where XX is the amount
	 * of upgrades added within the same date.
	 *
	 * The return value should not be modified manually. It gets added
	 * automatically by the .scripts/create_upgrade.php script.
	 *
	 * @return int Timestamp
	 */
	public function getVersion();

	/**
	 * Creation time of the upgrade file as a timestamp
	 *
	 * The return value should not be modified manually. It gets added
	 * automatically by the .scripts/create_upgrade.php script.
	 *
	 * @return string The Elgg release this upgrade was made against
	 */
	public function getRelease();
}
