<?php

namespace Elgg\WebServices\ApiMethods;

use Elgg\WebServices\Di\ApiRegistrationService;

/**
 * Api handler for the system.api.list call
 *
 * @since 4.0
 * @internal
 */
class SystemApiList {
	
	/**
	 * Execute the api method
	 *
	 * @return \GenericResult
	 */
	public function __invoke() {
		$apis = ApiRegistrationService::instance()->getAllApiMethods();
		
		$result = [];
		
		/* @var $api \Elgg\WebServices\ApiMethod */
		foreach ($apis as $api) {
			$result[$api->getID()] = [
				'description' => $api->description,
				'parameters' => $api->params,
				'call_method' => $api->call_method,
				'require_api_auth' => $api->require_api_auth,
				'require_user_auth' => $api->require_user_auth,
				'assoc' => $api->supply_associative,
			];
		}
		
		return \SuccessResult::getInstance($result);
	}
}
