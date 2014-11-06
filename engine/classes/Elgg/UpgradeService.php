<?php

/**
 * Upgrade service for Elgg
 *
 * This is a straight port of the procedural code used for upgrading before
 * Elgg 1.9.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Upgrade
 */
class Elgg_UpgradeService {

	/**
	 * Run the upgrade process
	 *
	 * @return array
	 */
	public function run() {
		$result = array(
			'failure' => false,
			'reason' => '',
		);

		// prevent someone from running the upgrade script in parallel (see #4643)
		if (!$this->getUpgradeMutex()) {
			$result['failure'] = true;
			$result['reason'] = elgg_echo('upgrade:locked');
			return $result;
		}

		// disable the system log for upgrades to avoid exceptions when the schema changes.
		elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');
		elgg_unregister_event_handler('all', 'all', 'system_log_listener');

		// turn off time limit
		set_time_limit(0);

		if ($this->getUnprocessedUpgrades()) {
			$this->processUpgrades();
		}

		elgg_trigger_event('upgrade', 'system', null);
		elgg_invalidate_simplecache();
		elgg_reset_system_cache();

		$this->releaseUpgradeMutex();

		return $result;
	}

	/**
	 * Run any php upgrade scripts which are required
	 *
	 * @param int  $version Version upgrading from.
	 * @param bool $quiet   Suppress errors.  Don't use this.
	 *
	 * @return bool
	 */
	protected function upgradeCode($version, $quiet = false) {
		$version = (int) $version;
		$upgrade_path = elgg_get_config('path') . 'engine/lib/upgrades/';
		$processed_upgrades = $this->getProcessedUpgrades();

		// upgrading from 1.7 to 1.8. Need to bootstrap.
		if (!$processed_upgrades) {
			$this->bootstrap17to18();

			// grab accurate processed upgrades
			$processed_upgrades = $this->getProcessedUpgrades();
		}

		$upgrade_files = $this->getUpgradeFiles($upgrade_path);

		if ($upgrade_files === false) {
			return false;
		}

		$upgrades = $this->getUnprocessedUpgrades($upgrade_files, $processed_upgrades);

		// Sort and execute
		sort($upgrades);

		foreach ($upgrades as $upgrade) {
			$upgrade_version = $this->getUpgradeFileVersion($upgrade);
			$success = true;

			// hide all errors.
			if ($quiet) {
				// hide include errors as well as any exceptions that might happen
				try {
					if (!@self::includeCode("$upgrade_path/$upgrade")) {
						$success = false;
						error_log("Could not include $upgrade_path/$upgrade");
					}
				} catch (Exception $e) {
					$success = false;
					error_log($e->getMessage());
				}
			} else {
				if (!self::includeCode("$upgrade_path/$upgrade")) {
					$success = false;
					error_log("Could not include $upgrade_path/$upgrade");
				}
			}

			if ($success) {
				// don't set the version to a lower number in instances where an upgrade
				// has been merged from a lower version of Elgg
				if ($upgrade_version > $version) {
					datalist_set('version', $upgrade_version);
				}

				// incrementally set upgrade so we know where to start if something fails.
				$this->setProcessedUpgrade($upgrade);
			} else {
				return false;
			}
		}

		return true;
	}

	/**
	 * PHP include a file with a very limited scope
	 *
	 * @param string $file File path to include
	 * @return mixed
	 */
	protected static function includeCode($file) {
		// do not remove - some upgrade scripts depend on this
		global $CONFIG;

		return include $file;
	}

	/**
	 * Saves a processed upgrade to a dataset.
	 *
	 * @param string $upgrade Filename of the processed upgrade
	 *                        (not the path, just the file)
	 * @return bool
	 */
	protected function setProcessedUpgrade($upgrade) {
		$processed_upgrades = $this->getProcessedUpgrades();
		$processed_upgrades[] = $upgrade;
		$processed_upgrades = array_unique($processed_upgrades);
		return datalist_set('processed_upgrades', serialize($processed_upgrades));
	}

	/**
	 * Gets a list of processes upgrades
	 *
	 * @return mixed Array of processed upgrade filenames or false
	 */
	protected function getProcessedUpgrades() {
		$upgrades = datalist_get('processed_upgrades');
		$unserialized = unserialize($upgrades);
		return $unserialized;
	}

	/**
	 * Returns the version of the upgrade filename.
	 *
	 * @param string $filename The upgrade filename. No full path.
	 * @return int|false
	 * @since 1.8.0
	 */
	protected function getUpgradeFileVersion($filename) {
		preg_match('/^([0-9]{10})([\.a-z0-9-_]+)?\.(php)$/i', $filename, $matches);

		if (isset($matches[1])) {
			return (int) $matches[1];
		}

		return false;
	}

	/**
	 * Returns a list of upgrade files relative to the $upgrade_path dir.
	 *
	 * @param string $upgrade_path The up
	 * @return array|false
	 */
	protected function getUpgradeFiles($upgrade_path = null) {
		if (!$upgrade_path) {
			$upgrade_path = elgg_get_config('path') . 'engine/lib/upgrades/';
		}
		$upgrade_path = sanitise_filepath($upgrade_path);
		$handle = opendir($upgrade_path);

		if (!$handle) {
			return false;
		}

		$upgrade_files = array();

		while ($upgrade_file = readdir($handle)) {
			// make sure this is a wellformed upgrade.
			if (is_dir($upgrade_path . '$upgrade_file')) {
				continue;
			}
			$upgrade_version = $this->getUpgradeFileVersion($upgrade_file);
			if (!$upgrade_version) {
				continue;
			}
			$upgrade_files[] = $upgrade_file;
		}

		sort($upgrade_files);

		return $upgrade_files;
	}

	/**
	 * Checks if any upgrades need to be run.
	 *
	 * @param null|array $upgrade_files      Optional upgrade files
	 * @param null|array $processed_upgrades Optional processed upgrades
	 *
	 * @return array
	 */
	protected function getUnprocessedUpgrades($upgrade_files = null, $processed_upgrades = null) {
		if ($upgrade_files === null) {
			$upgrade_files = $this->getUpgradeFiles();
		}

		if ($processed_upgrades === null) {
			$processed_upgrades = unserialize(datalist_get('processed_upgrades'));
			if (!is_array($processed_upgrades)) {
				$processed_upgrades = array();
			}
		}

		$unprocessed = array_diff($upgrade_files, $processed_upgrades);
		return $unprocessed;
	}

	/**
	 * Upgrades Elgg Database and code
	 *
	 * @return bool
	 */
	protected function processUpgrades() {

		$dbversion = (int) datalist_get('version');

		// No version number? Oh snap...this is an upgrade from a clean installation < 1.7.
		// Run all upgrades without error reporting and hope for the best.
		// See https://github.com/elgg/elgg/issues/1432 for more.
		$quiet = !$dbversion;

		// Note: Database upgrades are deprecated as of 1.8.  Use code upgrades.  See #1433
		if ($this->dbUpgrade($dbversion, '', $quiet)) {
			system_message(elgg_echo('upgrade:db'));
		}

		if ($this->upgradeCode($dbversion, $quiet)) {
			system_message(elgg_echo('upgrade:core'));

			// Now we trigger an event to give the option for plugins to do something
			$upgrade_details = new stdClass;
			$upgrade_details->from = $dbversion;
			$upgrade_details->to = elgg_get_version();

			elgg_trigger_event('upgrade', 'upgrade', $upgrade_details);

			return true;
		}

		return false;
	}

	/**
	 * Boot straps into 1.8 upgrade system from 1.7
	 *
	 * This runs all the 1.7 upgrades, then sets the processed_upgrades to all existing 1.7 upgrades.
	 * Control is then passed back to the main upgrade function which detects and runs the
	 * 1.8 upgrades, regardless of filename convention.
	 *
	 * @return bool
	 */
	protected function bootstrap17to18() {
		$db_version = (int) datalist_get('version');

		// the 1.8 upgrades before the upgrade system change that are interspersed with 1.7 upgrades.
		$upgrades_18 = array(
			'2010111501.php',
			'2010121601.php',
			'2010121602.php',
			'2010121701.php',
			'2010123101.php',
			'2011010101.php',
		);

		$upgrade_files = $this->getUpgradeFiles();
		$processed_upgrades = array();

		foreach ($upgrade_files as $upgrade_file) {
			// ignore if not in 1.7 format or if it's a 1.8 upgrade
			if (in_array($upgrade_file, $upgrades_18) || !preg_match("/[0-9]{10}\.php/", $upgrade_file)) {
				continue;
			}

			$upgrade_version = $this->getUpgradeFileVersion($upgrade_file);

			// this has already been run in a previous 1.7.X -> 1.7.X upgrade
			if ($upgrade_version < $db_version) {
				$this->setProcessedUpgrade($upgrade_file);
			}
		}
	}

	/**
	 * Creates a table {prefix}upgrade_lock that is used as a mutex for upgrades.
	 *
	 * @return bool
	 */
	protected function getUpgradeMutex() {
		global $CONFIG;

		if (!$this->isUpgradeLocked()) {
			// lock it
			insert_data("create table {$CONFIG->dbprefix}upgrade_lock (id INT)");
			elgg_log('Locked for upgrade.', 'NOTICE');
			return true;
		}

		elgg_log('Cannot lock for upgrade: already locked.', 'WARNING');
		return false;
	}

	/**
	 * Unlocks upgrade.
	 *
	 * @return void
	 */
	public function releaseUpgradeMutex() {
		global $CONFIG;
		delete_data("drop table {$CONFIG->dbprefix}upgrade_lock");
		elgg_log('Upgrade unlocked.', 'NOTICE');
	}

	/**
	 * Checks if upgrade is locked
	 *
	 * @return bool
	 */
	public function isUpgradeLocked() {
		global $CONFIG;

		$is_locked = count(get_data("SHOW TABLES LIKE '{$CONFIG->dbprefix}upgrade_lock'"));

		return (bool)$is_locked;
	}

	/**
	 * ***************************************************************************
	 * NOTE: If this is ever removed from Elgg, sites lose the ability to upgrade
	 * from 1.7.x and earlier to the latest version of Elgg without upgrading to
	 * 1.8 first.
	 * ***************************************************************************
	 *
	 * Upgrade the database schema in an ordered sequence.
	 *
	 * Executes all upgrade files in elgg/engine/schema/upgrades/ in sequential order.
	 * Upgrade files must be in the standard Elgg release format of YYYYMMDDII.sql
	 * where II is an incrementor starting from 01.
	 *
	 * Files that are < $version will be ignored.
	 *
	 * @param int    $version The version you are upgrading from in the format YYYYMMDDII.
	 * @param string $fromdir Optional directory to load upgrades from. default: engine/schema/upgrades/
	 * @param bool   $quiet   If true, suppress all error messages. Only use for the upgrade from <=1.6.
	 *
	 * @return int The number of upgrades run.
	 * @deprecated 1.8 Use PHP upgrades for sql changes.
	 */
	protected function dbUpgrade($version, $fromdir = "", $quiet = false) {
		global $CONFIG;

		$version = (int) $version;

		if (!$fromdir) {
			$fromdir = $CONFIG->path . 'engine/schema/upgrades/';
		}

		$i = 0;

		if ($handle = opendir($fromdir)) {
			$sqlupgrades = array();

			while ($sqlfile = readdir($handle)) {
				if (!is_dir($fromdir . $sqlfile)) {
					if (preg_match('/^([0-9]{10})\.(sql)$/', $sqlfile, $matches)) {
						$sql_version = (int) $matches[1];
						if ($sql_version > $version) {
							$sqlupgrades[] = $sqlfile;
						}
					}
				}
			}

			asort($sqlupgrades);

			if (sizeof($sqlupgrades) > 0) {
				foreach ($sqlupgrades as $sqlfile) {

					// hide all errors.
					if ($quiet) {
						try {
							run_sql_script($fromdir . $sqlfile);
						} catch (DatabaseException $e) {
							error_log($e->getmessage());
						}
					} else {
						run_sql_script($fromdir . $sqlfile);
					}
					$i++;
				}
			}
		}

		return $i;
	}

}
