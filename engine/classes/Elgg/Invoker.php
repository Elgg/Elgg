<?php

namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Invocation service
 */
class Invoker {

	/**
	 * @var SessionManagerService
	 */
	protected $session_manager;

	/**
	 * @var PublicContainer
	 */
	protected $dic;

	/**
	 * Constructor
	 *
	 * @param SessionManagerService $session_manager Session
	 * @param PublicContainer       $dic             DI container
	 */
	public function __construct(SessionManagerService $session_manager, PublicContainer $dic) {
		$this->session_manager = $session_manager;
		$this->dic = $dic;
	}

	/**
	 * Calls a callable autowiring the arguments using public DI services
	 * and applying logic based on flags
	 *
	 * @param int      $flags   Bitwise flags
	 *                          ELGG_IGNORE_ACCESS
	 *                          ELGG_ENFORCE_ACCESS
	 *                          ELGG_SHOW_DISABLED_ENTITIES
	 *                          ELGG_HIDE_DISABLED_ENTITIES
	 * @param \Closure $closure Callable to call
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function call(int $flags, \Closure $closure) {

		$ia = $this->session_manager->getIgnoreAccess();
		if ($flags & ELGG_IGNORE_ACCESS) {
			$this->session_manager->setIgnoreAccess(true);
		} else if ($flags & ELGG_ENFORCE_ACCESS) {
			$this->session_manager->setIgnoreAccess(false);
		}

		$ha = $this->session_manager->getDisabledEntityVisibility();
		if ($flags & ELGG_SHOW_DISABLED_ENTITIES) {
			$this->session_manager->setDisabledEntityVisibility(true);
		} else if ($flags & ELGG_HIDE_DISABLED_ENTITIES) {
			$this->session_manager->setDisabledEntityVisibility(false);
		}
		
		$system_log_enabled = null;
		$system_log_service = null;
		if ((($flags & ELGG_ENABLE_SYSTEM_LOG) || ($flags & ELGG_DISABLE_SYSTEM_LOG)) && elgg_is_active_plugin('system_log')) {
			try {
				$system_log_service = \Elgg\SystemLog\SystemLog::instance();
				$system_log_enabled = $system_log_service->isLoggingEnabled();
				
				if ($flags & ELGG_ENABLE_SYSTEM_LOG) {
					$system_log_service->enableLogging();
				} elseif ($flags & ELGG_DISABLE_SYSTEM_LOG) {
					$system_log_service->disableLogging();
				}
			} catch (\DI\NotFoundException $e) {
				// somehow the service isn't correctly registered
			}
		}

		$restore = function () use ($ia, $ha, $system_log_service, $system_log_enabled) {
			$this->session_manager->setIgnoreAccess($ia);
			$this->session_manager->setDisabledEntityVisibility($ha);
			
			if (isset($system_log_service)) {
				if ($system_log_enabled) {
					$system_log_service->enableLogging();
				} else {
					$system_log_service->disableLogging();
				}
			}
		};

		try {
			$result = $this->dic->call($closure);
		} catch (\Throwable $e) {
			$restore();
			throw $e;
		}
		
		$restore();

		return $result;
	}
}
