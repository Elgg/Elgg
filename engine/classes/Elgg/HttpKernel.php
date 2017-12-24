<?php

namespace Elgg;

/**
 * Web kernel
 */
class HttpKernel extends Kernel {

	/**
	 * {@inheritdoc}
	 */
	public function run() {
		$request = $this->application->_services->request;

		if (0 === strpos($request->getElggPath(), '/cache/')) {
			$this->cache_handler->handleRequest($request)->prepare($request)->send();

			return true;
		}

		if (0 === strpos($request->getElggPath(), '/serve-file/')) {
			$this->serve_file_handler->getResponse($request)->send();

			return true;
		}

		$this->application->boot();

		// re-fetch new request from services in case it was replaced by route:rewrite
		$request = $this->application->_services->request;

		if (!$this->application->_services->router->route($request)) {
			$this->application->_services->responseFactory->redirect('', ELGG_HTTP_NOT_FOUND);
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function testRewriteRules() {
		$rewriteTester = new \ElggRewriteTester();
		$url = $this->application->_services->config->wwwroot . "__testing_rewrite?__testing_rewrite=1";
		if (!$rewriteTester->runRewriteTest($url)) {
			// see if there is a problem accessing the site at all
			// due to ip restrictions for example
			if (!$rewriteTester->runLocalhostAccessTest()) {
				// note: translation may not be available until after upgrade
				$msg = elgg_echo("installation:htaccess:localhost:connectionfailed");
				if ($msg === "installation:htaccess:localhost:connectionfailed") {
					$msg = "Elgg cannot connect to itself to test rewrite rules properly. Check "
						. "that curl is working and there are no IP restrictions preventing "
						. "localhost connections.";
				}
				echo $msg;
				exit;
			}

			// note: translation may not be available until after upgrade
			$msg = elgg_echo("installation:htaccess:needs_upgrade");
			if ($msg === "installation:htaccess:needs_upgrade") {
				$msg = "You must update your .htaccess file (use install/config/htaccess.dist as a guide).";
			}
			echo $msg;
			exit;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function redirect($url, $reason = '') {
		$this->application->_services->responseFactory->redirect($url, $reason);
		exit;
	}
}
