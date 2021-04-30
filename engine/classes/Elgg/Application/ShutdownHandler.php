<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * Shutdown handler
 *
 * @internal
 */
class ShutdownHandler {

	use Loggable;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * Constructor
	 *
	 * @param Application $app Application
	 */
	public function __construct(Application $app) {
		$this->app = $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __invoke() {
		try {
			$this->shutdownDatabase();
			$this->shutdownApplication();
			$this->persistCaches();
		} catch (\Throwable $e) {
			$this->log(LogLevel::CRITICAL, $e);
		}
	}

	/**
	 * Shutdown the database, execute delayed queries and do some logging
	 * @return void
	 */
	public function shutdownDatabase() {
		$services = $this->app->_services;

		if ($services->db) {
			$services->db->executeDelayedQueries();

			$db_calls = $services->db->getQueryCount();
			$this->log(LogLevel::INFO, "DB Queries for this page: $db_calls");
		}
	}

	/**
	 * Emits a shutdown:system event upon PHP shutdown, but before database connections are dropped.
	 *
	 * @tip Register for the shutdown:system event to perform functions at the end of page loads.
	 *
	 * @warning  Using this event to perform long-running functions is not very
	 * useful.  Servers will hold pages until processing is done before sending
	 * them out to the browser.
	 *
	 * @see http://www.php.net/register-shutdown-function
	 *
	 * @internal This is registered in \Elgg\Application::create()
	 *
	 * @return void
	 */
	public function shutdownApplication() {
		$services = $this->app->_services;

		if (!$services->events->triggerBefore('shutdown', 'system')) {
			return;
		}

		$services->events->trigger('shutdown', 'system');

		global $GLOBALS;
		if (isset($GLOBALS['START_MICROTIME'])) {
			$time = (float) (microtime(true) - $GLOBALS['START_MICROTIME']);
			$uri = $services->request->server->get('REQUEST_URI', 'CLI');
			$this->log(LogLevel::INFO, "Page {$uri} generated in $time seconds");
		}

		$services->events->triggerAfter('shutdown', 'system');
	}

	/**
	 * Persist some of the core caches
	 * @return void
	 */
	public function persistCaches() {
		$this->app->_services->autoloadManager->saveCache();
	}
}
