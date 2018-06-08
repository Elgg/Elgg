<?php

namespace Elgg\Router\Middleware;
use Elgg\HttpException;
use Elgg\Request;

/**
 * Ensure that mod_rewrite is enabled and working
 */
class RewriteTest {

	/**
	 * Execute a rewrite test
	 *
	 * @param Request $request Request
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {

		$rewriteTester = new \ElggRewriteTester();
		$url = elgg_get_site_url() . "__testing_rewrite?__testing_rewrite=1";
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

				throw new HttpException($msg, ELGG_HTTP_INTERNAL_SERVER_ERROR);
			}

			// note: translation may not be available until after upgrade
			$msg = elgg_echo("installation:htaccess:needs_upgrade");
			if ($msg === "installation:htaccess:needs_upgrade") {
				$msg = "You must update your .htaccess file (use install/config/htaccess.dist as a guide).";
			}

			throw new HttpException($msg, ELGG_HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}