<?php

namespace Elgg;

use Elgg\Database\Mutex;
use Elgg\i18n\Translator;
use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Locator;
use Elgg\Upgrade\Result;
use ElggUpgrade;
use RuntimeException;

/**
 * Upgrade service for Elgg
 *
 * @access private
 */
class UpgradeService {

	/**
	 * @var Locator
	 */
	protected $locator;

	/**
	 * @var Translator
	 */
	private $translator;

	/**
	 * @var EventsService
	 */
	private $events;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var Mutex
	 */
	private $mutex;

	/**
	 * @var SystemMessagesService
	 */
	private $system_messages;

	/**
	 * Constructor
	 *
	 * @param Locator               $locator         Upgrade locator
	 * @param Translator            $translator      Translation service
	 * @param EventsService         $events          Events service
	 * @param Config                $config          Config
	 * @param Logger                $logger          Logger
	 * @param Mutex                 $mutex           Database mutex service
	 * @param SystemMessagesService $system_messages System messages
	 */
	public function __construct(
		Locator $locator,
		Translator $translator,
		EventsService $events,
		Config $config,
		Logger $logger,
		Mutex $mutex,
		SystemMessagesService $system_messages
	) {
		$this->locator = $locator;
		$this->translator = $translator;
		$this->events = $events;
		$this->config = $config;
		$this->logger = $logger;
		$this->mutex = $mutex;
		$this->system_messages = $system_messages;
	}

	/**
	 * Run the upgrade process
	 *
	 * @param bool $async Execute all async upgrades
	 * @return void
	 * @throws RuntimeException
	 */
	public function run($async = false) {
		// prevent someone from running the upgrade script in parallel (see #4643)
		if (!$this->mutex->lock('upgrade')) {
			throw new RuntimeException($this->translator->translate('upgrade:locked'));
		}

		// clear autoload cache so plugin classes can be reregistered and used during upgrade
		_elgg_services()->autoloadManager->deleteCache();

		// disable the system log for upgrades to avoid exceptions when the schema changes.
		$this->events->unregisterHandler('log', 'systemlog', 'system_log_default_logger');
		$this->events->unregisterHandler('all', 'all', 'system_log_listener');

		// turn off time limit
		set_time_limit(0);

		if ($this->getUnprocessedUpgrades()) {
			$this->processUpgrades();
		}

		$upgrades = $this->locator->locate();
		if ($async) {
			foreach ($upgrades as $upgrade) {
				$upgrade_name = $upgrade->getDisplayName();

				$this->logger->log("Starting upgrade {$upgrade_name}", Logger::NOTICE);

				$result = $this->executeAsyncUpgrade($upgrade, false);

				if (!empty($result['errors'])) {
					$msg = $this->translator->translate('admin:upgrades:completed:errors', [
						$upgrade_name,
						$upgrade->getCompletedTime(),
						implode(PHP_EOL, $result['errors']),
					]);
				} else {
					$msg = $this->translator->translate('admin:upgrades:completed', [
						$upgrade_name,
						$upgrade->getCompletedTime(),
					]);
				}

				$this->system_messages->addSuccessMessage($msg);

				$this->logger->log("Finished upgrade {$upgrade_name}", Logger::NOTICE);
			}
		}

		$this->events->trigger('upgrade', 'system', null);
		elgg_flush_caches();

		$this->mutex->unlock('upgrade');
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
					$this->config->save('version', $upgrade_version);
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
	 *
	 * @return bool
	 */
	protected function setProcessedUpgrade($upgrade) {
		$processed_upgrades = $this->getProcessedUpgrades();
		$processed_upgrades[] = $upgrade;
		$processed_upgrades = array_unique($processed_upgrades);

		return $this->config->save('processed_upgrades', $processed_upgrades);
	}

	/**
	 * Gets a list of processes upgrades
	 *
	 * @return mixed Array of processed upgrade filenames or false
	 */
	protected function getProcessedUpgrades() {
		return $this->config->processed_upgrades;
	}

	/**
	 * Returns the version of the upgrade filename.
	 *
	 * @param string $filename The upgrade filename. No full path.
	 *
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
	 *
	 * @return array|false
	 */
	protected function getUpgradeFiles($upgrade_path = null) {
		if (!$upgrade_path) {
			$upgrade_path = elgg_get_engine_path() . '/lib/upgrades/';
		}
		$upgrade_path = \Elgg\Project\Paths::sanitize($upgrade_path);
		$handle = opendir($upgrade_path);

		if (!$handle) {
			return false;
		}

		$upgrade_files = [];

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
			$processed_upgrades = $this->config->processed_upgrades;
			if (!is_array($processed_upgrades)) {
				$processed_upgrades = [];
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
		$dbversion = (int) $this->config->version;

		if ($this->upgradeCode($dbversion)) {
			$this->system_messages->addSuccessMessage($this->translator->translate('upgrade:core'));

			return true;
		}

		return false;
	}

	/**
	 * Get pending async upgrades
	 * @return ElggUpgrade[]
	 */
	public function getAsyncUpgrades() {
		return $this->locator->locate();
	}

	/**
	 * Call the upgrade's run() for a specified period of time, or until it completes
	 *
	 * @param ElggUpgrade $upgrade      Upgrade to run
	 * @param int         $max_duration Maximum duration in seconds
	 *                                  Set to false to execute an entire upgrade
	 *
	 * @return array
	 * @throws RuntimeException
	 */
	public function executeAsyncUpgrade(ElggUpgrade $upgrade, $max_duration = null) {
		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		$ha = _elgg_services()->session->getDisabledEntityVisibility();
		_elgg_services()->session->setDisabledEntityVisibility(true);

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$started = microtime(true);

		// Get the class taking care of the actual upgrading
		$batch = $upgrade->getBatch();

		if (!$batch) {
			throw new RuntimeException(elgg_echo('admin:upgrades:error:invalid_batch', [
				$upgrade->getDisplayName(),
				$upgrade->guid
			]));
		}

		$count = $batch->countItems();

		$batch_failure_count = 0;
		$batch_success_count = 0;
		$errors = [];

		$processed = (int) $upgrade->processed;
		$offset = (int) $upgrade->offset;
		$has_errors = (bool) $upgrade->has_errors;

		/** @var Result $result */
		$result = null;

		if (!isset($max_duration)) {
			$max_duration = $this->config->batch_run_time_in_secs;
		}

		$condition = function () use (&$count, &$processed, &$result, $started, $max_duration) {
			if ($max_duration && (microtime(true) - $started) >= $max_duration) {
				return false;
			}
			if ($result && $result->wasMarkedComplete()) {
				return false;
			}

			return ($count === Batch::UNKNOWN_COUNT || ($count > $processed));
		};

		while ($condition()) {
			$result = $batch->run(new Result(), $offset);

			$failure_count = $result->getFailureCount();
			$success_count = $result->getSuccessCount();

			$batch_failure_count += $failure_count;
			$batch_success_count += $success_count;

			$total = $failure_count + $success_count;

			if ($batch->needsIncrementOffset()) {
				// Offset needs to incremented by the total amount of processed
				// items so the upgrade we won't get stuck upgrading the same
				// items over and over.
				$offset += $total;
			} else {
				// Offset doesn't need to be incremented, so we mark only
				// the items that caused a failure.
				$offset += $failure_count;
			}

			if ($failure_count > 0) {
				$has_errors = true;
			}

			$processed += $total;

			$errors = array_merge($errors, $result->getErrors());
		}

		_elgg_services()->session->setIgnoreAccess($ia);
		_elgg_services()->session->setDisabledEntityVisibility($ha);

		$upgrade->processed = $processed;
		$upgrade->offset = $offset;
		$upgrade->has_errors = $has_errors;

		$completed = ($result && $result->wasMarkedComplete()) || ($processed >= $count);
		if ($completed) {
			// Upgrade is finished
			if ($has_errors) {
				// The upgrade was finished with errors. Reset offset
				// and errors so the upgrade can start from a scratch
				// if attempted to run again.
				$upgrade->processed = 0;
				$upgrade->offset = 0;
				$upgrade->has_errors = false;
			} else {
				// Everything has been processed without errors
				// so the upgrade can be marked as completed.
				$upgrade->setCompleted();
			}
		}

		// Give feedback to the user interface about the current batch.
		return [
			'errors' => $errors,
			'numErrors' => $batch_failure_count,
			'numSuccess' => $batch_success_count,
			'isComplete' => $result && $result->wasMarkedComplete(),
		];
	}
}
