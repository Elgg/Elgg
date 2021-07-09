<?php

namespace Elgg;

use Elgg\Cli\Progress;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Mutex;
use Elgg\I18n\Translator;
use Elgg\Traits\Loggable;
use Elgg\Upgrade\Locator;
use Elgg\Upgrade\Loop;
use Elgg\Upgrade\Result;
use function React\Promise\all;
use React\Promise\Deferred;
use React\Promise\Promise;

/**
 * Upgrade service for Elgg
 *
 * @internal
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
	 * @param Mutex                 $mutex           Database mutex service
	 * @param SystemMessagesService $system_messages System messages
	 * @param Progress              $progress        Progress
	 */
	public function __construct(
		Locator $locator,
		Translator $translator,
		EventsService $events,
		Config $config,
		Mutex $mutex,
		SystemMessagesService $system_messages,
		Progress $progress
	) {
		$this->locator = $locator;
		$this->translator = $translator;
		$this->events = $events;
		$this->config = $config;
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
				return $reject(new \RuntimeException($this->translator->translate('upgrade:terminated')));
			}

			// prevent someone from running the upgrade script in parallel (see #4643)
			if (!$this->mutex->lock('upgrade')) {
				return $reject(new \RuntimeException($this->translator->translate('upgrade:locked')));
			}

			// Clear system caches
			\Elgg\Cache\EventHandlers::disable();
			elgg_clear_caches();

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

			elgg_invalidate_caches();

			$this->mutex->unlock('upgrade');

			$this->events->triggerAfter('upgrade', 'system', null);

			return $resolve();
		});
	}

	/**
	 * Run system and async upgrades
	 *
	 * @param \ElggUpgrade[] $upgrades Upgrades to run
	 *
	 * @return Promise
	 */
	protected function runUpgrades($upgrades) {
		$promises = [];

		foreach ($upgrades as $upgrade) {
			if (!$upgrade instanceof \ElggUpgrade) {
				continue;
			}
			$promises[] = new Promise(function ($resolve, $reject) use ($upgrade) {
				try {
					$result = $this->executeUpgrade($upgrade, false);
				} catch (\Throwable $ex) {
					return $reject($ex);
				}

				if ($result->getFailureCount()) {
					$msg = elgg_echo('admin:upgrades:failed', [
						$upgrade->getDisplayName(),
					]);

					return $reject(new \RuntimeException($msg));
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
	 * @param \ElggUpgrade[] $upgrades Upgrades to run
	 *
	 * @return Promise
	 * @throws \RuntimeException
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
	 * Get pending async upgrades
	 *
	 * @param bool $async Include async upgrades
	 *
	 * @return \ElggUpgrade[]
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
			$pending = array_filter($pending, function(\ElggUpgrade $upgrade) {
				return !$upgrade->isAsynchronous();
			});
		}

		return $pending;
	}
	
	/**
	 * Get completed (async) upgrades ordered by recently completed first
	 *
	 * @param bool $async Include async upgrades
	 *
	 * @return \ElggUpgrade[]
	 */
	public function getCompletedUpgrades($async = true) {
		// make sure always to return all upgrade entities
		return elgg_call(
			ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES,
			function () use ($async) {
				$completed = [];
				
				$order_by_completed_time = new EntitySortByClause();
				$order_by_completed_time->direction = 'DESC';
				$order_by_completed_time->property = 'completed_time';
				$order_by_completed_time->property_type = 'private_setting';
				
				$upgrades = elgg_get_entities([
					'type' => 'object',
					'subtype' => 'elgg_upgrade',
					'private_setting_name' => 'class', // filters old upgrades
					'private_setting_name_value_pairs' => [
						'name' => 'is_completed',
						'value' => true,
					],
					'order_by' => $order_by_completed_time,
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
					$completed = array_filter($completed, function(\ElggUpgrade $upgrade) {
						return !$upgrade->isAsynchronous();
					});
				}
		
				return $completed;
			}
		);
	}

	/**
	 * Call the upgrade's run() for a specified period of time, or until it completes
	 *
	 * @param \ElggUpgrade $upgrade      Upgrade to run
	 * @param int          $max_duration Maximum duration in seconds
	 *                                   Set to false to execute an entire upgrade
	 *
	 * @return Result
	 * @throws \RuntimeException
	 */
	public function executeUpgrade(\ElggUpgrade $upgrade, $max_duration = null) {
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
						$this->getLogger()
					);
					
					$loop->loop($max_duration);
					
					return $result;
				});
			}
		);
	}
}
