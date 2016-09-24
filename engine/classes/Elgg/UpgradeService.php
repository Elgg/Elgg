<?php
namespace Elgg;

/**
 * Upgrade service for Elgg
 *
 * @access private
 */
class UpgradeService {

	/**
	 * @var \Elgg\i18n\Translator
	 */
	private $translator;

	/**
	 * @var \Elgg\EventsService
	 */
	private $events;

	/**
	 * @var \Elgg\PluginHooksService
	 */
	private $hooks;

	/**
	 * @var \Elgg\Database\Datalist
	 */
	private $datalist;

	/**
	 * @var \Elgg\Logger
	 */
	private $logger;

	/**
	 * @var \Elgg\Database\Mutex
	 */
	private $mutex;

	/**
	 * Constructor
	 *
	 * @param \Elgg\i18n\Translator    $translator Translation service
	 * @param \Elgg\EventsService      $events     Events service
	 * @param \Elgg\PluginHooksService $hooks      Plugin hook service
	 * @param \Elgg\Database\Datalist  $datalist   Datalist table
	 * @param \Elgg\Logger             $logger     Logger
	 * @param \Elgg\Database\Mutex     $mutex      Database mutex service
	 */
	public function __construct(
			\Elgg\i18n\Translator $translator,
			\Elgg\EventsService $events,
			\Elgg\PluginHooksService $hooks,
			\Elgg\Database\Datalist $datalist,
			\Elgg\Logger $logger,
			\Elgg\Database\Mutex $mutex) {
		$this->translator = $translator;
		$this->events = $events;
		$this->hooks = $hooks;
		$this->datalist = $datalist;
		$this->mutex = $mutex;
	}

	/**
	 * Run the upgrade process
	 *
	 * @return array $result Associative array containing possible errors
	 */
	public function run() {
		$result = array(
			'failure' => false,
			'reason' => '',
		);

		// prevent someone from running the upgrade script in parallel (see #4643)
		if (!$this->mutex->lock('upgrade')) {
			$result['failure'] = true;
			$result['reason'] = $this->translator->translate('upgrade:locked');
			return $result;
		}

		// disable the system log for upgrades to avoid exceptions when the schema changes.
		$this->events->unregisterHandler('log', 'systemlog', 'system_log_default_logger');
		$this->events->unregisterHandler('all', 'all', 'system_log_listener');

		// turn off time limit
		set_time_limit(0);

		if ($this->getUnprocessedUpgrades()) {
			$this->processUpgrades();
		}

		$this->events->trigger('upgrade', 'system', null);
		elgg_flush_caches();

		$this->mutex->unlock('upgrade');

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
		$upgrade_path = elgg_get_engine_path() . '/lib/upgrades/';
		$processed_upgrades = $this->getProcessedUpgrades();

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

			if ($upgrade_version <= $version) {
				// skip upgrade files from before the installation version of Elgg
				// because the upgrade files from before the installation version aren't
				// added to the database.
				continue;
			}

			// hide all errors.
			if ($quiet) {
				// hide include errors as well as any exceptions that might happen
				try {
					if (!@Includer::includeFile("$upgrade_path/$upgrade")) {
						$success = false;
						$this->logger->error("Could not include $upgrade_path/$upgrade");
					}
				} catch (\Exception $e) {
					$success = false;
					$this->logger->error($e->getMessage());
				}
			} else {
				if (!Includer::includeFile("$upgrade_path/$upgrade")) {
					$success = false;
					$this->logger->error("Could not include $upgrade_path/$upgrade");
				}
			}

			if ($success) {
				// don't set the version to a lower number in instances where an upgrade
				// has been merged from a lower version of Elgg
				if ($upgrade_version > $version) {
					$this->datalist->set('version', $upgrade_version);
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
		return $this->datalist->set('processed_upgrades', serialize($processed_upgrades));
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
			$upgrade_path = elgg_get_engine_path() . '/lib/upgrades/';
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
			$processed_upgrades = unserialize($this->datalist->get('processed_upgrades'));
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
		$dbversion = (int) $this->datalist->get('version');

		if ($this->upgradeCode($dbversion)) {
			system_message($this->translator->translate('upgrade:core'));

			// Now we trigger an event to give the option for plugins to do something
			$upgrade_details = new \stdClass;
			$upgrade_details->from = $dbversion;
			$upgrade_details->to = elgg_get_version();

			$this->events->trigger('upgrade', 'upgrade', $upgrade_details);

			return true;
		}

		return false;
	}
}

