<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\CliKernel;
use Elgg\Http\OkResponse;
use Elgg\Kernel;

/**
 * Handle upgrades
 *
 * @access private
 */
class UpgradeHandler {

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
	 * Elgg upgrade script.
	 *
	 * This script triggers any necessary upgrades. If the site has been upgraded
	 * to the most recent version of the code, no upgrades are run but the caches
	 * are flushed.
	 *
	 * Upgrades use a table {db_prefix}upgrade_lock as a mutex to prevent concurrent upgrades.
	 *
	 * The URL to forward to after upgrades are complete can be specified by setting $_GET['forward']
	 * to a relative URL.
	 *
	 * @return void
	 * @throws \InstallationException
	 */
	public function handleUpgrade() {
		// we want to know if an error occurs
		ini_set('display_errors', 1);
		$is_cli = $this->kernel instanceof CliKernel;

		define('UPGRADING', 'upgrading');

		$this->application->migrate();
		$this->application->start();

		// clear autoload cache so plugin classes can be reregistered and used during upgrade
		$this->application->_services->autoloadManager->deleteCache();

		// check security settings
		if ($this->application->_services->config->security_protect_upgrade && !elgg_is_admin_logged_in()) {
			// only admin's or users with a valid token can run upgrade.php
			elgg_signed_request_gatekeeper();
		}

		$site_url = $this->application->_services->config->url;
		$site_host = parse_url($site_url, PHP_URL_HOST) . '/';

		// turn any full in-site URLs into absolute paths
		$forward_url = get_input('forward', '/admin', false);
		$forward_url = str_replace([$site_url, $site_host], '/', $forward_url);

		if (strpos($forward_url, '/') !== 0) {
			$forward_url = '/' . $forward_url;
		}

		if ($is_cli || (get_input('upgrade') == 'upgrade')) {
			$upgrader = $this->application->_services->upgrades;
			$result = $upgrader->run();

			if ($result['failure'] == true) {
				register_error($result['reason']);
				return $this->kernel->redirect($forward_url);
			}

			// Find unprocessed batch upgrade classes and save them as ElggUpgrade objects
			$core_upgrades = (require $this->application->elggDir()->getPath('engine/lib/upgrades/async-upgrades.php'));
			$has_pending_upgrades = $this->application->_services->upgradeLocator->run($core_upgrades);

			if ($has_pending_upgrades) {
				// Forward to the list of pending upgrades
				$forward_url = '/admin/upgrades';
			}
		} else {
			$this->kernel->testRewriteRules();

			$vars = [
				'forward' => $forward_url
			];

			// reset cache to have latest translations available during upgrade
			elgg_reset_system_cache();

			$output = elgg_view_page(elgg_echo('upgrading'), '', 'upgrade', $vars);
			$response = new OkResponse($output);
			return $this->application->_services->responseFactory->redirect($response);
		}

		return $this->kernel->redirect($forward_url);
	}
}
