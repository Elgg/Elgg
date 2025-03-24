<?php

namespace Elgg\Upgrade;

use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Elgg\Traits\Loggable;
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
	 * @return null|ResponseBuilder
	 * @throws InternalServerErrorException
	 */
	public function __invoke(Request $request): ?ResponseBuilder {
		$response = null;

		$forward_url = $request->getParam('forward', 'admin');
		$forward_url = elgg_normalize_site_url($forward_url);

		$upgrade = _elgg_services()->upgrades->run();

		$upgrade->then(
			function () use (&$response, $forward_url) {
				$response = elgg_ok_response('', elgg_echo('upgrade:core'), $forward_url);
			},
			function ($error) use ($forward_url) {
				$this->log(LogLevel::ERROR, $error);

				$exception = new InternalServerErrorException($error);
				$exception->setRedirectUrl($forward_url);

				throw $exception;
			}
		);
		
		_elgg_services()->plugins->generateEntities();

		return $response;
	}
}
