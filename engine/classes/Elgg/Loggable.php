<?php

namespace Elgg;

use Psr\Log\LoggerInterface;

/**
 * Enables adding a logger. Users should not assume $this->logger is set: use Loggable::getLogger()
 *
 * @access private
 */
trait Loggable {

	/**
	 * @var Logger|null
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

}
