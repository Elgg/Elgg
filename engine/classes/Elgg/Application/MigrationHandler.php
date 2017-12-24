<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Kernel;

/**
 * Handle migrations
 *
 * @access private
 */
class MigrationHandler {

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
	 * Runs database migrations
	 *
	 * @throws \InstallationException
	 * @return bool
	 */
	public function handleMigrations() {
		$conf = $this->application->elggDir()->getPath('engine/conf/migrations.php');
		if (!$conf) {
			throw new \InstallationException('Settings file is required to run database migrations.');
		}

		$app = new \Phinx\Console\PhinxApplication();
		$wrapper = new \Phinx\Wrapper\TextWrapper($app, [
			'configuration' => $conf,
		]);
		$log = $wrapper->getMigrate();

		if (!empty($_SERVER['argv']) && in_array('--verbose', $_SERVER['argv'])) {
			error_log($log);
		}

		return true;
	}
}
