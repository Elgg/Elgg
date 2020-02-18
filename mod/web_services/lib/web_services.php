<?php
/**
 * Elgg web services API library
 * Functions and objects for exposing custom web services.
 */

/**
 * Get POST data
 *
 * Since this is called through a handler, we need to manually get the post data
 *
 * @return false|string POST data as string encoded as multipart/form-data
 *
 * @link https://www.php.net/manual/en/wrappers.php.php#wrappers.php.input
 * @internal
 */
function elgg_ws_get_post_data() {
	return file_get_contents('php://input');
}

/**
 * This function extracts the various header variables needed for the HMAC PAM
 *
 * @return stdClass Containing all the values
 *
 * @throws APIException Detailing any error
 * @internal
 */
function elgg_ws_get_and_validate_api_headers() {
	$result = new stdClass;

	$result->method = _elgg_services()->request->getMethod();
	// Only allow these methods
	if (!in_array($result->method, ['GET', 'POST'])) {
		throw new APIException(elgg_echo('APIException:NotGetOrPost'));
	}

	$server = _elgg_services()->request->server;

	$result->api_key = $server->get('HTTP_X_ELGG_APIKEY');
	if (empty($result->api_key)) {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}

	$result->hmac = $server->get('HTTP_X_ELGG_HMAC');
	if (empty($result->hmac)) {
		throw new APIException(elgg_echo('APIException:MissingHmac'));
	}

	$result->hmac_algo = $server->get('HTTP_X_ELGG_HMAC_ALGO');
	if (empty($result->hmac_algo)) {
		throw new APIException(elgg_echo('APIException:MissingHmacAlgo'));
	}

	$result->time = $server->get('HTTP_X_ELGG_TIME');
	if (empty($result->time)) {
		throw new APIException(elgg_echo('APIException:MissingTime'));
	}

	// Must have been sent within 25 hour period.
	// 25 hours is more than enough to handle server clock drift.
	// This values determines how long the HMAC cache needs to store previous
	// signatures. Heavy use of HMAC is better handled with a shorter sig lifetime.
	// @see elgg_ws_cache_hmac_check_replay()
	if (($result->time < (time() - 90000)) || ($result->time > (time() + 90000))) {
		throw new APIException(elgg_echo('APIException:TemporalDrift'));
	}

	$result->nonce = $server->get('HTTP_X_ELGG_NONCE');
	if (empty($result->nonce)) {
		throw new APIException(elgg_echo('APIException:MissingNonce'));
	}

	if ($result->method === 'POST') {
		$result->posthash = $server->get('HTTP_X_ELGG_POSTHASH');
		if (empty($result->posthash)) {
			throw new APIException(elgg_echo('APIException:MissingPOSTHash'));
		}

		$result->posthash_algo = $server->get('HTTP_X_ELGG_POSTHASH_ALGO');
		if (empty($result->posthash_algo)) {
			throw new APIException(elgg_echo('APIException:MissingPOSTAlgo'));
		}

		$result->content_type = $server->get('CONTENT_TYPE');
		if (empty($result->content_type)) {
			throw new APIException(elgg_echo('APIException:MissingContentType'));
		}
	}

	return $result;
}

/**
 * Map various algorithms to their PHP equivs
 *
 * This also gives us an easy way to disable algorithms
 *
 * @param string $algo The algorithm
 *
 * @return string The php algorithm
 *
 * @throws APIException if an algorithm is not supported.
 * @internal
 */
function elgg_ws_map_api_hash(string $algo) {
	$algo = strtolower($algo);
	
	$supported_algos = [
		'sha' => 'sha1', // alias for sha1
		'sha1' => 'sha1',
		'sha256' => 'sha256',
	];

	if (array_key_exists($algo, $supported_algos)) {
		return $supported_algos[$algo];
	}

	throw new APIException(elgg_echo('APIException:AlgorithmNotSupported', [$algo]));
}

/**
 * Calculate the HMAC for the http request
 *
 * This function signs an api request using the information provided. The signature returned
 * has been base64 encoded and then url encoded
 *
 * @param string $algo          The HMAC algorithm used
 * @param string $time          String representation of unix time
 * @param string $nonce         Nonce
 * @param string $api_key       Your api key
 * @param string $secret_key    Your private key
 * @param string $get_variables URLEncoded string representation of the get variable parameters,
 *                              eg "method=user&guid=2"
 * @param string $post_hash     Optional sha1 hash of the post data
 *
 * @return string The HMAC signature
 *
 * @internal
 */
function elgg_ws_calculate_hmac($algo, $time, $nonce, $api_key, $secret_key, $get_variables, $post_hash = '') {

	elgg_log("HMAC Parts: $algo, $time, $api_key, $secret_key, $get_variables, $post_hash");

	$ctx = hash_init(elgg_ws_map_api_hash($algo), HASH_HMAC, $secret_key);

	hash_update($ctx, trim($time));
	hash_update($ctx, trim($nonce));
	hash_update($ctx, trim($api_key));
	hash_update($ctx, trim($get_variables));
	if (trim($post_hash) != "") {
		hash_update($ctx, trim($post_hash));
	}

	return urlencode(base64_encode(hash_final($ctx, true)));
}

/**
 * Calculate a hash for some post data
 *
 * @param string $postdata The post data
 * @param string $algo     The algorithm used
 *
 * @return string The hash
 *
 * @internal
 */
function elgg_ws_calculate_posthash($postdata, $algo) {
	$ctx = hash_init(elgg_ws_map_api_hash($algo));

	hash_update($ctx, $postdata);

	return hash_final($ctx);
}

/**
 * This function will do two things. Firstly it verifies that a HMAC signature
 * hasn't been seen before, and secondly it will add the given hmac to the cache
 *
 * @param string $hmac The hmac string
 *
 * @return bool True if replay detected, false if not
 *
 * @internal
 */
function elgg_ws_cache_hmac_check_replay($hmac) {
	if (_elgg_services()->hmacCacheTable->loadHMAC($hmac)) {
		return true;
	}
	
	_elgg_services()->hmacCacheTable->storeHMAC($hmac);
	
	return false;
}
