<?php

namespace Elgg\WebServices\PAM\API;

/**
 * Validate an API call with API keys
 * Used for the 'api' policy
 *
 * @internal
 * @since 4.3
 */
class APIKey {
	
	/**
	 * Confirm that the call includes a valid API key
	 *
	 * @return bool
	 * @throws \APIException
	 */
	public function __invoke(): bool {
		// check that an API key is present
		$api_key = (string) get_input('api_key');
		if ($api_key === '') {
			throw new \APIException(elgg_echo('APIException:MissingAPIKey'));
		}
		
		// check that it is active
		$api_user = _elgg_services()->apiUsersTable->getApiUser($api_key);
		if (!$api_user) {
			// key is not active or does not exist
			throw new \APIException(elgg_echo('APIException:BadAPIKey'));
		}
		
		// can be used for keeping stats
		// plugin can also return false to fail this authentication method
		return elgg_trigger_event_results('api_key', 'use', ['apikey' => $api_key], true);
	}
}
