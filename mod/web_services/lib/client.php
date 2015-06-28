<?php
/**
 * A library for building an API client
 */

/**
 * Send a raw API call to another Elgg api endpoint
 *
 * @param array  $keys         API keys <code>array('public' => $public, 'private' => $private)</code>
 * @param string $url          URL of the endpoint
 * @param array  $query        URL query elements as an array of key => value pairs
 * @param string $method       Call method: GET|POST
 * @param string $post_data    Data to be posted as payload
 * @param string $content_type The content type being posted
 * @return string
 */
function send_api_call(array $keys = array(), $url, array $query = array(), $method = 'GET', $post_data = '', $content_type = null) {

	$public_key = elgg_extract('public', $keys);
	$private_key = elgg_extract('private', $keys);

	$endpoint = elgg_http_add_url_query_elements($url, (array) $query);

	$client = new \Elgg\WebServices\Client($public_key, $private_key);
	return $client->call($method, $endpoint, $post_data, $content_type);
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
 * Send a POST call
 *
 * @param string $url          URL of the endpoint.
 * @param array  $call         Associated array of "variable" => "value"
 * @param array  $keys         The keys dependant on chosen authentication method
 * @param string $post_data    The post data
 * @param string $content_type The content type
 *
 * @return string
 */
function send_api_post_call($url, array $call, array $keys, $post_data, $content_type = null) {
	return send_api_call($keys, $url, $call, 'POST', $post_data, $content_type);
}
