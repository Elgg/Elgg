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
 * @return false|string POST data as string encoded when using content-type=application/x-www-form-urlencoded
 *
 * @link https://www.php.net/manual/en/wrappers.php.php#wrappers.php.input
 * @internal
 */
function elgg_ws_get_post_data(): string|false {
	return _elgg_services()->request->getContent();
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
function elgg_ws_map_api_hash(string $algo): string {
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
function elgg_ws_calculate_hmac(string $algo, string $time, string $nonce, string $api_key, string $secret_key, string $get_variables, string $post_hash = ''): string {

	elgg_log("HMAC Parts: {$algo}, {$time}, {$api_key}, {$secret_key}, {$get_variables}, {$post_hash}", \Psr\Log\LogLevel::INFO);

	$ctx = hash_init(elgg_ws_map_api_hash($algo), HASH_HMAC, $secret_key);

	hash_update($ctx, trim($time));
	hash_update($ctx, trim($nonce));
	hash_update($ctx, trim($api_key));
	hash_update($ctx, trim($get_variables));
	if (trim($post_hash) !== '') {
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
function elgg_ws_calculate_posthash(string $postdata, string $algo): string {
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
function elgg_ws_cache_hmac_check_replay(string $hmac): bool {
	if (_elgg_services()->hmacCacheTable->loadHMAC($hmac)) {
		return true;
	}
	
	_elgg_services()->hmacCacheTable->storeHMAC($hmac);
	
	return false;
}
