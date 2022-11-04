<?php

namespace Elgg\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

/**
 * Helper class to construct a Guzzle Client with the correct defaults
 *
 * @since 5.0
 */
class Client extends GuzzleClient {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $options = []) {
		
		$proxy_config = (array) elgg_get_config('proxy', []);
		
		$defaults = [
			RequestOptions::TIMEOUT => 5,
			RequestOptions::HTTP_ERRORS => false,
			RequestOptions::VERIFY => (bool) elgg_extract('verify_ssl', $proxy_config, true),
		];
		
		$host = elgg_extract('host', $proxy_config);
		if (!empty($host)) {
			$port = (int) elgg_extract('port', $proxy_config);
			if ($port > 0) {
				$host = rtrim($host, ':') . ":{$port}";
			}
			
			$defaults[RequestOptions::PROXY] = $host;
		}
		
		$options = array_merge($defaults, $options);
		
		parent::__construct($options);
	}
}
