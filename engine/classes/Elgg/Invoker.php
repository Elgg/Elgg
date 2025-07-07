<?php

namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Invocation service
 */
class Invoker {

	/**
	 * Constructor
	 *
	 * @param SessionManagerService $session_manager Session
	 * @param PublicContainer       $dic             DI container
	 */
	public function __construct(protected SessionManagerService $session_manager, protected PublicContainer $dic) {
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
	 *                          ELGG_SHOW_DELETED_ENTITIES
	 *                          ELGG_HIDE_DELETED_ENTITIES
	 * @param \Closure $closure Callable to call
	 *
	 * @return mixed
	 * @throws \Throwable
	 */
	public function call(int $flags, \Closure $closure) {

		$access = $this->session_manager->getIgnoreAccess();
		if ($flags & ELGG_IGNORE_ACCESS) {
			$this->session_manager->setIgnoreAccess(true);
		} elseif ($flags & ELGG_ENFORCE_ACCESS) {
			$this->session_manager->setIgnoreAccess(false);
		}

		$disabled = $this->session_manager->getDisabledEntityVisibility();
		if ($flags & ELGG_SHOW_DISABLED_ENTITIES) {
			$this->session_manager->setDisabledEntityVisibility(true);
		} elseif ($flags & ELGG_HIDE_DISABLED_ENTITIES) {
			$this->session_manager->setDisabledEntityVisibility(false);
		}

		$deleted = $this->session_manager->getDeletedEntityVisibility();
		if ($flags & ELGG_SHOW_DELETED_ENTITIES) {
			$this->session_manager->setDeletedEntityVisibility(true);
		} elseif ($flags & ELGG_HIDE_DELETED_ENTITIES) {
			$this->session_manager->setDeletedEntityVisibility(false);
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
				// somehow the service isn't correctly registered or unavailable
			}
		}

		$restore = function () use ($access, $disabled, $deleted, $system_log_service, $system_log_enabled) {
			$this->session_manager->setIgnoreAccess($access);
			$this->session_manager->setDisabledEntityVisibility($disabled);
			$this->session_manager->setDeletedEntityVisibility($deleted);
			
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
