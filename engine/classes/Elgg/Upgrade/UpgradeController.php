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

		$response = null;

		$forward_url = $request->getParam('forward', 'admin');
		$forward_url = elgg_normalize_site_url($forward_url);

		$upgrade = _elgg_services()->upgrades->run();

		$upgrade->done(
			function () use (&$response, $forward_url) {
				$response = elgg_redirect_response($forward_url);
			},
			function ($error) use ($forward_url) {
				$this->log(LogLevel::ERROR, $error);

				$exception = new HttpException($error, ELGG_HTTP_INTERNAL_SERVER_ERROR);
				$exception->setRedirectUrl($forward_url);

				throw $exception;
			}
		);

		return $response;
	}
}