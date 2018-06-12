<?php

namespace Elgg\Router\Middleware;

use Elgg\Http\ResponseBuilder;
use Elgg\HttpException;

/**
 * Protect upgrade.php from unauthorized execution
 */
class UpgradeGatekeeper {

	/**
	 * Protect upgrade.php from unauthorized execution
	 *
	 * @param \Elgg\Request $request Request
	 *
	 * @return ResponseBuilder|null
	 * @throws HttpException
	 */
	public function __invoke(\Elgg\Request $request) {

		if (elgg_is_admin_logged_in()) {
			return null;
		}

		if (!_elgg_config()->security_protect_upgrade) {
			return null;
		}

		$url = $request->getURL();
		if (!_elgg_services()->urlSigner->isValid($url)) {
			throw new HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
		}
	}

}
