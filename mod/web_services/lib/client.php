<?php
/**
 * A library for building an API client
 *
 * Load the library 'elgg:ws:client' to use the functions in this library
 */

/**
 * Send a raw API call to an elgg api endpoint.
 *
 * @param array  $keys         The api keys.
 * @param string $url          URL of the endpoint.
 * @param array  $call         Associated array of "variable" => "value"
 * @param string $method       GET or POST
 * @param string $post_data    The post data
 * @param string $content_type The content type
 *
 * @return string
 */
function send_api_call(array $keys, $url, array $call, $method = 'GET', $post_data = '',
$content_type = 'application/octet-stream') {

	$headers = [];
	$encoded_params = [];

	$method = strtoupper($method);
	switch (strtoupper($method)) {
		case 'GET' :
		case 'POST' :
			break;
		default:
			$msg = elgg_echo('NotImplementedException:CallMethodNotImplemented', [$method]);
			throw new NotImplementedException($msg);
	}

	// Time
	$time = time();

	// Nonce
	$nonce = uniqid('');

	// URL encode all the parameters
	foreach ($call as $k => $v) {
		$encoded_params[] = urlencode($k) . '=' . urlencode($v);
	}

	$params = implode('&', $encoded_params);

	// Put together the query string
	$url = $url . "?" . $params;

	// Construct headers
	$posthash = "";
	if ($method == 'POST') {
		$posthash = calculate_posthash($post_data, 'md5');
	}

	if ((isset($keys['public'])) && (isset($keys['private']))) {
		$headers['X-Elgg-apikey'] = $keys['public'];
		$headers['X-Elgg-time'] = $time;
		$headers['X-Elgg-nonce'] = $nonce;
		$headers['X-Elgg-hmac-algo'] = 'sha1';
		$headers['X-Elgg-hmac'] = calculate_hmac('sha1',
			$time,
			$nonce,
			$keys['public'],
			$keys['private'],
			$params,
			$posthash
		);
	}
	if ($method == 'POST') {
		$headers['X-Elgg-posthash'] = $posthash;
		$headers['X-Elgg-posthash-algo'] = 'md5';

		$headers['Content-type'] = $content_type;
		$headers['Content-Length'] = strlen($post_data);
	}

	// Opt array
	$http_opts = [
		'method' => $method,
		'header' => serialise_api_headers($headers)
	];
	if ($method == 'POST') {
		$http_opts['content'] = $post_data;
	}

	$opts = ['http' => $http_opts];

	// Send context
	$context = stream_context_create($opts);

	// Send the query and get the result and decode.
	elgg_log("APICALL: $url");
	$results = file_get_contents($url, false, $context);

	return $results;
}

/**
 * Send a GET call
 *
 * @param string $url  URL of the endpoint.
 * @param array  $call Associated array of "variable" => "value"
 * @param array  $keys The keys dependant on chosen authentication method
 *
 * @return string
 */
function send_api_get_call($url, array $call, array $keys) {
	return send_api_call($keys, $url, $call);
}

/**
 * Send a GET call
 *
 * @param string $url          URL of the endpoint.
 * @param array  $call         Associated array of "variable" => "value"
 * @param array  $keys         The keys dependant on chosen authentication method
 * @param string $post_data    The post data
 * @param string $content_type The content type
 *
 * @return string
 */
function send_api_post_call($url, array $call, array $keys, $post_data,
$content_type = 'application/octet-stream') {

	return send_api_call($keys, $url, $call, 'POST', $post_data, $content_type);
}

/**
 * Return a key array suitable for the API client using the standard
 * authentication method based on api-keys and secret keys.
 *
 * @param string $secret_key Your secret key
 * @param string $api_key    Your api key
 *
 * @return array
 */
function get_standard_api_key_array($secret_key, $api_key) {
	return ['public' => $api_key, 'private' => $secret_key];
}

/**
 * Utility function to serialise a header array into its text representation.
 *
 * @param array $headers The array of headers "key" => "value"
 *
 * @return string
 * @access private
 */
function serialise_api_headers(array $headers) {
	$headers_str = "";

	foreach ($headers as $k => $v) {
		$headers_str .= trim($k) . ": " . trim($v) . "\r\n";
	}

	return trim($headers_str);
}
