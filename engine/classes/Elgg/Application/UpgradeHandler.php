<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Http\RedirectResponse;
use Elgg\Http\ResponseBuilder;
use Elgg\Loggable;
use Psr\Log\LogLevel;

/**
 * Handles system upgrade
 */
class UpgradeHandler {

	use Loggable;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * Constructor
	 *
	 * @param Application $app Application instance
	 *                         Note that the application has not yet been booted
	 */
	public function __construct(Application $app) {
		$this->app = $app;
	}

	/**
	 * Start the upgrade process
	 * We need to migrate the database and boot the application to finalize all migrations
	 *
	 * @return void
	 */
	private function start() {
		Application::$_upgrading = true;
		Application::migrate();
	}

	/**
	 * Finish the upgrade process
	 * @return void
	 */
	private function end() {
		Application::$_upgrading = false;
	}

	/**
	 * Run the upgrades
	 *
	 * @param bool $async Execute asynchronous upgrades
	 * @return ResponseBuilder
	 */
	public function run($async = false) {

		$this->start();

		$config = $this->app->_services->config;
		$request = $this->app->_services->request;

		if (!$config->boot_complete) {
			$this->app->bootCore();
		}

		if ('cli' === PHP_SAPI) {
			$www_root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/';
			$config->wwwroot = $www_root;
			$config->wwwroot_cli_server = $www_root;

			try {
				_elgg_services()->upgrades->run($async);
				$response = new OkResponse();
			} catch (\Exception $ex) {
				$this->getLogger()->log($ex, LogLevel::ERROR);
				$response = new ErrorResponse($ex->getMessage(), $ex->getCode() ? : 500);
			}
		} else {
			$response = new RedirectResponse('upgrade/init');
		}

		$this->end();

		return $response;
	}

}