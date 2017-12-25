<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Kernel;

/**
 * Shutdown handler
 *
 * @access private
 */
class ShutdownHandler {

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * @var Kernel
	 */
	protected $kernel;

	/**
	 * Kernel constructor.
	 *
	 * @param Application $application Application
	 * @param Kernel      $kernel      Kernel
	 */
	public function __construct(Application $application, Kernel $kernel) {
		$this->application = $application;
		$this->kernel = $kernel;
	}

	/**
	 * Emits a shutdown:system event upon PHP shutdown, but before database connections are dropped.
	 *
	 * @tip     Register for the shutdown:system event to perform functions at the end of page loads.
	 *
	 * @warning Using this event to perform long-running functions is not very
	 * useful.  Servers will hold pages until processing is done before sending
	 * them out to the browser.
	 *
	 * @see     http://www.php.net/register-shutdown-function
	 *
	 * @return void
	 */
	public function handleShutdown() {
		try {
			if ($this->application->_services->db) {
				$this->application->_services->db->executeDelayedQueries();

				$db_calls = $this->application->_services->db->getQueryCount();

				// demoted to NOTICE as it corrupts javascript at DEBUG
				$this->application->_services->logger->info("DB Queries for this page: $db_calls");
			}

			$this->application->_services->logger->setDisplay(false);

			$this->application->_services->hooks->getEvents()->trigger('shutdown', 'system');

			$time = (float) (microtime(true) - $GLOBALS['START_MICROTIME']);
			$uri = $this->application->_services->request->server->get('REQUEST_URI', 'CLI');

			// demoted to NOTICE from DEBUG so javascript is not corrupted
			$this->application->_services->logger->info("Page {$uri} generated in $time seconds");
		} catch (\Exception $e) {
			$message = 'Error: ' . get_class($e) . ' thrown within the shutdown handler. ';
			$message .= "Message: '{$e->getMessage()}' in file {$e->getFile()} (line {$e->getLine()})";
			error_log($message);
			error_log("Exception trace stack: {$e->getTraceAsString()}");
		}

		// Prevent an APC session bug: https://bugs.php.net/bug.php?id=60657
		session_write_close();
	}
}
