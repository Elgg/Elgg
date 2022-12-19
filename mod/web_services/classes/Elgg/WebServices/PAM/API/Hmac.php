<?php

namespace Elgg\WebServices\PAM\API;

use Elgg\Exceptions\SecurityException;

/**
 * Confirm the HMAC signature of an API call
 * Used for the 'api' policy
 *
 * @internal
 * @since 4.3
 */
class Hmac {
	
	/**
	 * Validate the HMAC signature of an API call
	 *
	 * @return bool
	 * @throws \Elgg\Exceptions\SecurityException
	 */
	public function __invoke(): bool {
		// Get api header
		$api_header = elgg_ws_get_and_validate_api_headers();
		
		// Pull API user details
		$api_user = _elgg_services()->apiUsersTable->getApiUser($api_header->api_key);
		
		if (!$api_user) {
			throw new SecurityException(elgg_echo('SecurityException:InvalidAPIKey'), \ErrorResult::RESULT_FAIL_APIKEY_INVALID);
		}
		
		// calculate expected HMAC
		$hmac = elgg_ws_calculate_hmac(
			$api_header->hmac_algo,
			$api_header->time,
			$api_header->nonce,
			$api_header->api_key,
			$api_user->secret,
			_elgg_services()->request->server->get('QUERY_STRING', ''),
			$api_header->method === 'POST' ? $api_header->posthash : ''
		);
		
		if ($api_header->hmac !== $hmac) {
			throw new SecurityException("HMAC is invalid. {$api_header->hmac} != [calc]{$hmac}");
		}
		
		// Now make sure this is not a replay
		if (elgg_ws_cache_hmac_check_replay($hmac)) {
			throw new SecurityException(elgg_echo('SecurityException:DupePacket'));
		}
		
		// Validate post data
		if ($api_header->method === 'POST') {
			$postdata = elgg_ws_get_post_data();
			$calculated_posthash = elgg_ws_calculate_posthash($postdata, $api_header->posthash_algo);
			
			if ($api_header->posthash !== $calculated_posthash) {
				throw new SecurityException(elgg_echo('SecurityException:InvalidPostHash', [$calculated_posthash, $api_header->posthash]));
			}
		}
		
		return true;
	}
}
