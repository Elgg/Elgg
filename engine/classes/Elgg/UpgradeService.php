<?php
namespace Elgg;

use Elgg\i18n\Translator;
use Elgg\Database\Datalist;
use Elgg\Logger;
use Elgg\Database;
use ElggSession;

/**
 * Upgrade service for Elgg
 *
 * @access private
 */
class UpgradeService {

	private $translator;

	private $datalist;

	private $logger;

	private $db;

	private $upgrade;

	private $batch_run_time_in_secs;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->translator = _elgg_services()->translator;
		$this->datalist = _elgg_services()->datalist;
		$this->logger = _elgg_services()->logger;
		$this->db = _elgg_services()->db;
		$this->session = _elgg_services()->session;

		// TODO Make configurable
		$batch_run_time_in_secs = 2;
	}

	/**
	 * Prepare the system to run an upgrade
	 *
	 * @param Upgrade $upgrade The upgrade object
	 * @param int     $offset  Current offset of the items to upgrade
	 * @return array $result Array containing possible errors
	 */
	public function prepareUpgrade(\Elgg\Upgrades\Upgrade $upgrade, $offset = null) {
		$this->upgrade = $upgrade;

		if ($offset) {
			$this->upgrade->setOffset($offset);
		}

		// Admin isn't necessarily logged in when running this,
		// so we need to ignore access permissions
		$this->session->setIgnoreAccess(true);

		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		global $ENTITY_SHOW_HIDDEN_OVERRIDE;
		$ENTITY_SHOW_HIDDEN_OVERRIDE = true;

		$result = array(
			'failure' => false,
			'reason' => '',
		);

		// Prevent someone from running the upgrade script in parallel (see #4643)
		if (!$this->getUpgradeMutex()) {
			$result['failure'] = true;
			$result['reason'] = $this->translator->translate('upgrade:locked');
			return $result;
		}

		// Turn off time limit
		set_time_limit(0);
	}

	/**
	 * Run a single upgrade
	 *
	 * @return array Number of successes, errors and the next offset
	 */
	public function runUpgrade() {
		if ($this->upgrade instanceof \Elgg\Upgrades\BatchUpgrade) {
			// from engine/start.php
			global $START_MICROTIME;

			do {
				$this->upgrade->run();

				// TODO Remove after debugging
				sleep(1);

			} while ((microtime(true) - $START_MICROTIME) < $this->batch_run_time_in_secs);

			$result = array(
				'numSuccess' => $this->upgrade->getSuccessCount(),
				'numErrors' => $this->upgrade->getErrorCount(),
				'nextOffset' => $this->upgrade->getNextOffset(),
			);
		} else {
			$result = $this->upgrade->run();

			if ($result) {
				$result = array(
					'numSuccess' => 1,
					'numErrors' => 0,
				);
			} else {
				$result = array(
					'numSuccess' => 0,
					'numErrors' => 1,
				);
			}
		}

		$this->resetAccess();

		return $result;
	}

	/**
	 * Returns system back to normal state
	 *
	 * Releases upgrade mutex, hides hidden entities and
	 * resets access to normal.
	 *
	 * @return void
	 */
	public function resetAccess() {
		$this->releaseUpgradeMutex();

		global $ENTITY_SHOW_HIDDEN_OVERRIDE;
		$ENTITY_SHOW_HIDDEN_OVERRIDE = false;

		$this->session->setIgnoreAccess(false);
	}

	/**
	 * Saves a processed upgrade to a dataset and updates site version
	 *
	 * @param Elgg\Upgrades\Upgrade $upgrade The upgrade object
	 * @return void
	 */
	public function setProcessedUpgrade(\Elgg\Upgrades\Upgrade $upgrade) {
		$unique_name = $this->getUniqueName($upgrade);

		$processed_upgrades = $this->getProcessedUpgrades();
		$processed_upgrades[] = $unique_name;
		$processed_upgrades = array_unique($processed_upgrades);

		// Save the upgrade to the list of successful upgrades
		$this->datalist->set('processed_upgrades', serialize($processed_upgrades));

		// Version in format yyyymmdd00 where the last two are increments of upgrades per day
		$version = (int) $this->datalist->get('version');

		// Don't set the version to a lower number in instances where an upgrade
		// has been merged from a lower version of Elgg
		if ($upgrade->getVersion() > $version) {
			$this->datalist->set('version', $upgrade->getVersion());
		}
	}

	/**
	 * Gets a list of processes upgrades
	 *
	 * @return mixed Array of processed upgrade filenames or false
	 */
	protected function getProcessedUpgrades() {
		$upgrades = $this->datalist->get('processed_upgrades');
		$unserialized = unserialize($upgrades);
		return $unserialized;
	}

	/**
	 * Returns an array of upgrade objects
	 *
	 * @return array Array with (Unique id => Instance of the upgrade)
	 */
	protected function getUpgradeFiles() {
		$upgrade_paths[] = _elgg_services()->config->get('path') . 'engine/classes/Elgg/Upgrades';

		$plugins = _elgg_services()->plugins->find('all');
		foreach ($plugins as $plugin) {
			$dir = "{$plugin->getPath()}classes/Elgg/Upgrades/";

			if (is_dir($dir)) {
				$upgrade_paths[] = $dir;
			}
		}

		$upgrades = array();
		foreach ($upgrade_paths as $upgrade_path) {
			$upgrade_path = sanitise_filepath($upgrade_path);

			$dir = new \DirectoryIterator($upgrade_path);

			foreach ($dir as $file) {
				/* @var \SplFileInfo $file */
				if (!$file->isFile() || !$file->isReadable()) {
					// TODO Log a warning
					continue;
				}

				$class_name = $file->getBasename('.php');
				$full_class_name = '\Elgg\Upgrades\\' . $class_name;

				if (!class_exists($full_class_name)) {
					// TODO Log a warning
					continue;
				}

				$instance = new $full_class_name;

				if (!$instance instanceof \Elgg\Upgrades\Upgrade) {
					// TODO Log a warning
					continue;
				}

				$upgrades[$this->getUniqueName($instance)] = $instance;
			}
		}

		// Sort by creation time YYYYMMDD00
		ksort($upgrades);

		return $upgrades;
	}

	/**
	 * Get an unique name that can be used to identify the upgrade
	 *
	 * @param Elgg\Upgrades\Upgrade $upgrade The upgrade object
	 * @return string
	 */
	private function getUniqueName($upgrade) {
		$version = $upgrade->getVersion();
		$release = $upgrade->getRelease();
		$class_name = get_class($upgrade);

		return "{$version}-{$release}-{$class_name}";
	}

	/**
	 * Checks if any upgrades need to be run.
	 *
	 * @return array Associative array of upgrade objects
	 */
	public function getUnprocessedUpgrades() {
		$upgrades = $this->getUpgradeFiles();

		$processed_upgrades = unserialize($this->datalist->get('processed_upgrades'));
		if (!is_array($processed_upgrades)) {
			$processed_upgrades = array();
		}

		foreach ($upgrades as $key => $upgrade) {
			if (!$upgrade->isRequired()) {
				$this->setProcessedUpgrade($upgrade);
				unset($upgrades[$key]);
			}
		}

		return array_diff_key($upgrades, array_flip($processed_upgrades));
	}

	/**
	 * Creates a table {prefix}upgrade_lock that is used as a mutex for upgrades.
	 *
	 * @return bool
	 */
	protected function getUpgradeMutex() {
		if (!$this->isUpgradeLocked()) {
			$db_prefix = $this->db->getTablePrefix();

			// lock it
			$this->db->insertData("create table {$db_prefix}upgrade_lock (id INT)");
			$this->logger->notice('Locked for upgrade.');
			return true;
		}

		$this->logger->warn('Cannot lock for upgrade: already locked');
		return false;
	}

	/**
	 * Unlocks upgrade
	 *
	 * @return void
	 */
	public function releaseUpgradeMutex() {
		$db_prefix = $this->db->getTablePrefix();
		$this->logger->notice('Upgrade unlocked.');

		// Cannot return the result because amount of addected rows is zero
		$this->db->deleteData("drop table {$db_prefix}upgrade_lock");
	}

	/**
	 * Checks if upgrade is locked
	 *
	 * @return bool
	 */
	public function isUpgradeLocked() {
		$db_prefix = $this->db->getTablePrefix();
		$is_locked = count($this->db->getData("SHOW TABLES LIKE '{$db_prefix}upgrade_lock'"));
		return (bool) $is_locked;
	}
}
