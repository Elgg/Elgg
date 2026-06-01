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
	 * @return bool|null
	 * @throws \Elgg\Exceptions\SecurityException
	 */
	public function __invoke(): ?bool {
		if (!elgg_get_plugin_setting('auth_allow_hmac', 'web_services')) {
			return null;
		}
		
		// Get api header
		$api_header = $this->getHeaderInformation();
		if (!isset($api_header)) {
			return null;
		}
		
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
	
	/**
	 * This function extracts the various header variables needed for the HMAC PAM
	 *
	 * @return null|\stdClass Containing all the values
	 * @throws \APIException Detailing any error
	 */
	protected function getHeaderInformation(): ?\stdClass {
		$server = _elgg_services()->request->server;
		
		$header_keys = [
			'HTTP_X_ELGG_APIKEY',
			'HTTP_X_ELGG_HMAC',
			'HTTP_X_ELGG_HMAC_ALGO',
			'HTTP_X_ELGG_TIME',
			'HTTP_X_ELGG_NONCE',
		];
		
		$found = false;
		foreach ($header_keys as $key) {
			$value = $server->get($key);
			if (isset($value)) {
				$found = true;
				break;
			}
		}
		
		if (!$found) {
			return null;
		}
		
		$result = new \stdClass;
		
		$result->method = _elgg_services()->request->getMethod();
		// Only allow these methods
		if (!in_array($result->method, ['GET', 'POST'])) {
			throw new \APIException(elgg_echo('APIException:NotGetOrPost'));
		}
		
		$result->api_key = $server->get('HTTP_X_ELGG_APIKEY');
		if (empty($result->api_key)) {
			throw new \APIException(elgg_echo('APIException:MissingAPIKey'));
		}
		
		$result->hmac = $server->get('HTTP_X_ELGG_HMAC');
		if (empty($result->hmac)) {
			throw new \APIException(elgg_echo('APIException:MissingHmac'));
		}
		
		$result->hmac_algo = $server->get('HTTP_X_ELGG_HMAC_ALGO');
		if (empty($result->hmac_algo)) {
			throw new \APIException(elgg_echo('APIException:MissingHmacAlgo'));
		}
		
		$result->time = $server->get('HTTP_X_ELGG_TIME');
		if (empty($result->time)) {
			throw new \APIException(elgg_echo('APIException:MissingTime'));
		}
		
		// Must have been sent within 25 hour period.
		// 25 hours is more than enough to handle server clock drift.
		// This values determines how long the HMAC cache needs to store previous
		// signatures. Heavy use of HMAC is better handled with a shorter sig lifetime.
		// @see elgg_ws_cache_hmac_check_replay()
		if (($result->time < (time() - 90000)) || ($result->time > (time() + 90000))) {
			throw new \APIException(elgg_echo('APIException:TemporalDrift'));
		}
		
		$result->nonce = $server->get('HTTP_X_ELGG_NONCE');
		if (empty($result->nonce)) {
			throw new \APIException(elgg_echo('APIException:MissingNonce'));
		}
		
		if ($result->method === 'POST') {
			$result->posthash = $server->get('HTTP_X_ELGG_POSTHASH');
			if (empty($result->posthash)) {
				throw new \APIException(elgg_echo('APIException:MissingPOSTHash'));
			}
			
			$result->posthash_algo = $server->get('HTTP_X_ELGG_POSTHASH_ALGO');
			if (empty($result->posthash_algo)) {
				throw new \APIException(elgg_echo('APIException:MissingPOSTAlgo'));
			}
			
			$result->content_type = $server->get('CONTENT_TYPE');
			if (empty($result->content_type)) {
				throw new \APIException(elgg_echo('APIException:MissingContentType'));
			}
		}
		
		return $result;
	}
}
