<?php

namespace Elgg;

use Closure;
use Elgg\Di\PublicContainer;
use ElggSession;

/**
 * Invocation service
 */
class Invoker {

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var PublicContainer
	 */
	protected $dic;

	/**
	 * Constructor
	 *
	 * @param ElggSession     $session Session
	 * @param PublicContainer $dic     DI container
	 */
	public function __construct(ElggSession $session, PublicContainer $dic) {
		$this->session = $session;
		$this->dic = $dic;
	}

	/**
	 * Calls a callable autowiring the arguments using public DI services
	 * and applying logic based on flags
	 *
	 * @param int     $flags   Bitwise flags
	 *                         ELGG_IGNORE_ACCESS
	 *                         ELGG_ENFORCE_ACCESS
	 *                         ELGG_SHOW_DISABLED_ENTITIES
	 *                         ELGG_HIDE_DISABLED_ENTITIES
	 * @param Closure $closure Callable to call
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function call(int $flags, Closure $closure) {

		$ia = $this->session->getIgnoreAccess();
		if ($flags & ELGG_IGNORE_ACCESS) {
			$this->session->setIgnoreAccess(true);
		} else if ($flags & ELGG_ENFORCE_ACCESS) {
			$this->session->setIgnoreAccess(false);
		}

		$ha = $this->session->getDisabledEntityVisibility();
		if ($flags & ELGG_SHOW_DISABLED_ENTITIES) {
			$this->session->setDisabledEntityVisibility(true);
		} else if ($flags & ELGG_HIDE_DISABLED_ENTITIES) {
			$this->session->setDisabledEntityVisibility(false);
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
			$this->session->setIgnoreAccess($ia);
			$this->session->setDisabledEntityVisibility($ha);
			
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
