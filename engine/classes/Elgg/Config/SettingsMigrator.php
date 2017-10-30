<?php
namespace Elgg\Config;

use Elgg\Database;

/**
 * Migrates DB values to settings.php
 *
 * @access private
 */
abstract class SettingsMigrator {

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $settings_path;

	/**
	 * Constructor
	 *
	 * @param Database $db            Database
	 * @param string   $settings_path settings.php file path
	 *
	 * @return void
	 */
	public function __construct(Database $db, $settings_path) {
		$this->db = $db;
		$this->settings_path = $settings_path;
	}

	/**
	 * Write bytes to settings files
	 *
	 * @param string $bytes Text to write to the settings file
	 * @return bool
	 */
	protected function append($bytes) {
		$result = file_put_contents($this->settings_path, $bytes, FILE_APPEND | LOCK_EX);
		if ($result === false) {
			return false;
		}

		return true;
	}

	/**
	 * Attempt to read the setting from the database and update the settings file
	 * Returns the value found in the database or null
	 *
	 * @return mixed|null
	 */
	abstract public function migrate();
}
