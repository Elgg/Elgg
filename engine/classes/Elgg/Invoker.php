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

		$restore = function () use ($ia, $ha) {
			$this->session->setIgnoreAccess($ia);
			$this->session->setDisabledEntityVisibility($ha);
		};

		try {
			$result = $this->dic->call($closure);
		} catch (\Exception $e) {
			$restore();
			throw $e;
		}

		$restore();

		return $result;
	}
}
