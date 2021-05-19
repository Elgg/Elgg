<?php

namespace Elgg\Traits;

use Elgg\Application;
use Elgg\Logger;
use Psr\Log\LoggerInterface;

/**
 * Enables adding a logger. Users should not assume $this->logger is set: use Loggable::getLogger()
 *
 * @internal
 */
trait Loggable {

	/**
	 * @var LoggerInterface|null
	 */
	private $logger;

	/**
	 * Set (or remove) the logger
	 *
	 * @param LoggerInterface $logger Logger or null
	 *
	 * @return void
	 */
	public function setLogger(LoggerInterface $logger = null) {
		$this->logger = $logger;
	}

	/**
	 * Returns logger
	 *
	 * @return LoggerInterface
	 */
	public function getLogger() {

		if ($this->logger) {
			return $this->logger;
		} else if (Application::$_instance) {
			return Application::$_instance->_services->logger;
		}

		// Application hasn't been bootstrapped
		return Logger::factory();
	}

	/**
	 * Log a message
	 *
	 * @param string $level   Severity
	 * @param mixed  $message Message
	 * @param array  $context Context
	 *
	 * @return bool
	 */
	public function log($level, $message, array $context = []) {
		if ($message instanceof \Throwable) {
			if (!isset($message->timestamp)) {
				$message->timestamp = time();
			}

			$context['exception'] = $message;
		}

		// PSR interface is void, but Monolog returns a boolean
		$logged = $this->getLogger()->log($level, $message, $context);
		return isset($logged) ? $logged : true;
	}
	
	/**
	 * Sends a message about deprecated use of a function, view, etc.
	 *
	 * @param string $message Message to log
	 * @param string $version Human-readable *release* version: 1.7, 1.8, ...
	 *
	 * @return void
	 */
	public function logDeprecatedMessage(string $message, string $version): void {
		$this->getLogger()->warning("Deprecated in {$version}: {$message}");
	}
}
