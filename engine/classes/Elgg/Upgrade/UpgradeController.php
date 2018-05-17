<?php

namespace Elgg\Upgrade;

use Elgg\Http\ResponseBuilder;
use Elgg\HttpException;
use Elgg\Loggable;
use Elgg\Request;
use Psr\Log\LogLevel;

/**
 * Execute upgrades
 */
class UpgradeController {

	use Loggable;

	/**
	 * Execute system upgrades
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {

		$forward_url = $request->getParam('forward', 'admin');
		$forward_url = elgg_normalize_site_url($forward_url);

		try {
			_elgg_services()->upgrades->run();

			return elgg_redirect_response($forward_url);
		} catch (\Exception $ex) {
			$this->log(LogLevel::ERROR, $ex);

			$exception = new HttpException($ex->getMessage(), ELGG_HTTP_INTERNAL_SERVER_ERROR);
			$exception->setRedirectUrl($forward_url);

			throw $exception;
		}
	}
}