<?php

namespace Elgg;

use Elgg\Cli\Progress;
use Elgg\Database\Mutex;
use Elgg\i18n\Translator;
use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Locator;
use Elgg\Upgrade\Loop;
use Elgg\Upgrade\Result;
use ElggUpgrade;
use Psr\Log\LogLevel;
use function React\Promise\all;
use React\Promise\Deferred;
use React\Promise\Promise;
use RuntimeException;
use Throwable;

/**
 * Upgrade service for Elgg
 *
 * @access private
 */
class UpgradeService {

	use Loggable;

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
	 * @var Mutex
	 */
	private $mutex;

	/**
	 * @var SystemMessagesService
	 */
	private $system_messages;

	/**
	 * @var Progress
	 */
	protected $progress;

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
	 * @param Progress              $progress        Progress
	 */
	public function __construct(
		Locator $locator,
		Translator $translator,
		EventsService $events,
		Config $config,
		Logger $logger,
		Mutex $mutex,
		SystemMessagesService $system_messages,
		Progress $progress
	) {
		$this->locator = $locator;
		$this->translator = $translator;
		$this->events = $events;
		$this->config = $config;
		$this->logger = $logger;
		$this->mutex = $mutex;
		$this->system_messages = $system_messages;
		$this->progress = $progress;
	}

	/**
	 * Start an upgrade process
	 * @return Promise
	 */
	protected function up() {
		return new Promise(function ($resolve, $reject) {
			Application::migrate();

			if (!$this->events->triggerBefore('upgrade', 'system', null)) {
				return $reject(new RuntimeException($this->translator->translate('upgrade:terminated')));
			}

			// prevent someone from running the upgrade script in parallel (see #4643)
			if (!$this->mutex->lock('upgrade')) {
				return $reject(new RuntimeException($this->translator->translate('upgrade:locked')));
			}

			// Clear system caches
			_elgg_disable_caches();
			_elgg_clear_caches();

			return $resolve();
		});
	}

	/**
	 * Finish an upgrade process
	 * @return Promise
	 */
	protected function down() {
		return new Promise(function ($resolve, $reject) {
			if (!$this->events->trigger('upgrade', 'system', null)) {
				return $reject();
			}

			elgg_flush_caches();

			$this->mutex->unlock('upgrade');

			$this->events->triggerAfter('upgrade', 'system', null);

			return $resolve();
		});
	}

	/**
	 * Run legacy upgrade scripts
	 * @return Promise
	 * @deprecated 3.0
	 * @codeCoverageIgnore
	 */
	protected function runLegacyUpgrades() {
		return new Promise(function ($resolve, $reject) {
			if ($this->getUnprocessedUpgrades()) {
				$this->processUpgrades();
			}

			return $resolve();
		});
	}

	/**
	 * Run system and async upgrades
	 *
	 * @param ElggUpgrade[] $upgrades Upgrades to run
	 *
	 * @return Promise
	 */
	protected function runUpgrades($upgrades) {
		$promises = [];

		foreach ($upgrades as $key => $upgrade) {
			if (!$upgrade instanceof ElggUpgrade) {
				continue;
			}
			$promises[] = new Promise(function ($resolve, $reject) use ($upgrade) {
				try {
					$result = $this->executeUpgrade($upgrade, false);
				} catch (Throwable $ex) {
					return $reject($ex);
				}

				if ($result->getFailureCount()) {
					$msg = elgg_echo('admin:upgrades:failed', [
						$upgrade->getDisplayName(),
					]);

					return $reject(new RuntimeException($msg));
				} else {
					return $resolve($result);
				}
			});
		}

		return all($promises);
	}

	/**
	 * Run the upgrade process
	 *
	 * @param ElggUpgrade[] $upgrades Upgrades to run
	 *
	 * @return Promise
	 * @throws RuntimeException
	 */
	public function run($upgrades = null) {
		// turn off time limit
		set_time_limit(3600);

		$deferred = new Deferred();

		$promise = $deferred->promise();

		$resolve = function ($value) use ($deferred) {
			$deferred->resolve($value);
		};

		$reject = function ($error) use ($deferred) {
			$deferred->reject($error);
		};

		if (!isset($upgrades)) {
			$upgrades = $this->getPendingUpgrades(false);
		}

		$this->up()->done(
			function () use ($resolve, $reject, $upgrades) {
				all([
					$this->runLegacyUpgrades(),
					$this->runUpgrades($upgrades),
				])->done(
					function () use ($resolve, $reject) {
						$this->down()->done(
							function ($result) use ($resolve) {
								return $resolve($result);
							},
							$reject
						);
					},
					$reject
				);
			},
			$reject
		);

		return $promise;
	}

	/**
	 * Run any php upgrade scripts which are required
	 *
	 * @param int  $version Version upgrading from.
	 * @param bool $quiet   Suppress errors.  Don't use this.
	 *
	 * @return bool
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
					$this->logger->error($e);
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 * @deprecated 3.0
	 * @codeCoverageIgnore
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
	 *
	 * @param bool $async Include async upgrades
	 *
	 * @return ElggUpgrade[]
	 */
	public function getPendingUpgrades($async = true) {
		$pending = [];

		$upgrades = $this->locator->locate();

		foreach ($upgrades as $upgrade) {
			if ($upgrade->isCompleted()) {
				continue;
			}

			$batch = $upgrade->getBatch();
			if (!$batch) {
				continue;
			}

			$pending[] = $upgrade;
		}

		if (!$async) {
			$pending = array_filter($pending, function(ElggUpgrade $upgrade) {
				return !$upgrade->isAsynchronous();
			});
		}

		return $pending;
	}
	
	/**
	 * Get completed (async) upgrades
	 *
	 * @param bool $async Include async upgrades
	 *
	 * @return ElggUpgrade[]
	 */
	public function getCompletedUpgrades($async = true) {
		$completed = [];
		
		$upgrades = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'elgg_upgrade',
			'private_setting_name' => 'class', // filters old upgrades
			'private_setting_name_value_pairs' => [
				'name' => 'is_completed',
				'value' => true,
			],
			'limit' => false,
			'batch' => true,
		]);
		/* @var $upgrade \ElggUpgrade */
		foreach ($upgrades as $upgrade) {
			$batch = $upgrade->getBatch();
			if (!$batch) {
				continue;
			}

			$completed[] = $upgrade;
		}

		if (!$async) {
			$completed = array_filter($completed, function(ElggUpgrade $upgrade) {
				return !$upgrade->isAsynchronous();
			});
		}

		return $completed;
	}

	/**
	 * Call the upgrade's run() for a specified period of time, or until it completes
	 *
	 * @param ElggUpgrade $upgrade      Upgrade to run
	 * @param int         $max_duration Maximum duration in seconds
	 *                                  Set to false to execute an entire upgrade
	 *
	 * @return Result
	 * @throws RuntimeException
	 */
	public function executeUpgrade(ElggUpgrade $upgrade, $max_duration = null) {
		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		return elgg_call(
			ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES,
			function () use ($upgrade, $max_duration) {
				return $this->events->triggerSequence('upgrade:execute', 'system', $upgrade, function() use ($upgrade, $max_duration) {
					$result = new Result();
					
					$loop = new Loop(
						$upgrade,
						$result,
						$this->progress,
						$this->logger
					);
					
					$loop->loop($max_duration);
					
					return $result;
				});
			}
		);
	}
}
