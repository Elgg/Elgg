<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Di\ApplicationContainer;
use Elgg\Kernel;

/**
 * Handle installation
 *
 * @access private
 */
class InstallationHandler {

	/**
	 * Renders a web UI for installing Elgg.
	 *
	 * @return bool
	 * @throws \InstallationException
	 */
	public function handleInstall() {
		ini_set('display_errors', 1);

		$installer = new \ElggInstaller();
		$response = $installer->run();

		try {
			// we won't trust server configuration but specify utf-8
			elgg_set_http_header('Content-type: text/html; charset=utf-8');

			// turn off browser caching
			elgg_set_http_header('Pragma: public', true);
			elgg_set_http_header("Cache-Control: no-cache, must-revalidate", true);
			elgg_set_http_header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

			ApplicationContainer::getInstance()->services->responseFactory->respond($response);

			return headers_sent();
		} catch (\InvalidParameterException $ex) {
			throw new \InstallationException($ex->getMessage());
		}
	}
}
