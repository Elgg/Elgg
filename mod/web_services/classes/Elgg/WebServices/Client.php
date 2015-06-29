<?php

namespace Elgg\WebServices;

/**
 * API Client that allows making GET and POST calls to other Elgg API endpoints
 */
class Client {

	/**
	 * Public API key of the remote resource
	 * @var string
	 */
	private $public_key;

	/**
	 * Private API key of the remote resource
	 * @var string
	 */
	private $private_key;

	/**
	 * Constructor
	 * 
	 * @param string $public_key Public API key of the remote resource
	 * @param string $private_key Private API key of the remote resource
	 */
	public function __construct($public_key = null, $private_key = null) {
		$this->public_key = $public_key;
		$this->private_key = $private_key;
	}

	/**
	 * Send a raw API call to another Elgg API endpoint
	 *
	 * @param string $method       Call method: GET|POST
	 * @param string $endpoint     Endpoint URL (with query elements)
	 * @param string $post_data    Payload
	 * @param string $content_type Content type of the payload
	 * @return string|null Response
	 * @throws NotImplementedException
	 */
	public function call($method = 'GET', $endpoint = '', $post_data = '', $content_type = null) {

		$method = strtoupper($method);
		if (!in_array($method, array('GET', 'POST'))) {
			$msg = elgg_echo('NotImplementedException:CallMethodNotImplemented', array($method));
			throw new NotImplementedException($msg);
		}

		if (!$endpoint) {
			return;
		}

		$headers = array();

		// Time
		$time = time();

		// Nonce
		$nonce = uniqid('');

		$params = parse_url($endpoint, PHP_URL_QUERY);
		
		// Construct headers
		$posthash = "";
		if ($method == 'POST') {
			$posthash = calculate_posthash($post_data, 'md5');
		}

		if ($this->public_key && $this->private_key) {
			$headers['X-Elgg-apikey'] = $this->public_key;
			$headers['X-Elgg-time'] = $time;
			$headers['X-Elgg-nonce'] = $nonce;
			$headers['X-Elgg-hmac-algo'] = 'sha1';
			$headers['X-Elgg-hmac'] = calculate_hmac('sha1', $time, $nonce, $this->public_key, $this->private_key, $params, $posthash);
		}

		if ($method == 'POST') {
			$headers['X-Elgg-posthash'] = $posthash;
			$headers['X-Elgg-posthash-algo'] = 'md5';

			$headers['Content-type'] = ($content_type) ?: 'application/octet-stream';
			$headers['Content-Length'] = strlen($post_data);
		}

		// Opt array
		$http_opts = array(
			'method' => $method,
			'header' => $this->serializeApiHeaders($headers)
		);
		if ($method == 'POST') {
			$http_opts['content'] = $post_data;
		}

		$opts = array('http' => $http_opts);

		// Send context
		$context = stream_context_create($opts);

		// Send the query and get the result and decode.
		elgg_log("APICALL: $endpoint");
		$results = file_get_contents($endpoint, false, $context);

		return $results;
	}

	/**
	 * Send a GET call
	 *
	 * @param string $endpoint URL of the endpoint with query elements
	 * @return string
	 */
	public function get($endpoint) {
		return $this->call('GET', $endpoint);
	}

	/**
	 * Send a POST call
	 *
	 * @param string $endpoint URL of the endpoint.
	 * @param string $post_data    The post data
	 * @param string $content_type The content type
	 *
	 * @return string
	 */
	public function post($endpoint, $post_data, $content_type = null) {
		return $this->call('POST', $endpoint, $post_data, $content_type);
	}

	/**
	 * Utility function to serialise a header array into its text representation
	 *
	 * @param array $headers The array of headers "key" => "value"
	 * @return string
	 * @access private
	 */
	protected function serializeApiHeaders(array $headers = array()) {
		$headers_str = "";
		foreach ($headers as $k => $v) {
			$headers_str .= trim($k) . ": " . trim($v) . "\r\n";
		}
		return trim($headers_str);
	}

}
