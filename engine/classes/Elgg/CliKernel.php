<?php

namespace Elgg;

use Elgg\Project\Paths;

/**
 * Cli kernel
 */
class CliKernel extends Kernel {

	/**
	 * {@inheritdoc}
	 */
	public function run() {
		$config = $this->application->_services->config;
		$request = $this->application->_services->request;

		if ($request->isCliServer() && $request->isCliServable(Paths::project())) {
			// Let the cli server handle the request
			return false;
		}

		// overwrite value from settings
		$www_root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/';
		$config->wwwroot = $www_root;
		$config->wwwroot_cli_server = $www_root;

		$this->application->boot();

		// re-fetch new request from services in case it was replaced by route:rewrite
		$request = $this->application->_services->request;

		if (!$this->application->_services->router->route($request)) {
			$this->application->_services->responseFactory->redirect('', ELGG_HTTP_NOT_FOUND);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function testRewriteRules() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function redirect($url, $reason = '') {
		$this->application->_services->printer->write("Open $url in your browser to continue." . PHP_EOL, true, Logger::INFO);
	}
}
