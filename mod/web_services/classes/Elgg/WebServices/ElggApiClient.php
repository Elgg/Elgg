<?php

namespace Elgg\WebServices;

use Elgg\Traits\TimeUsing;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

/**
 * API client to call other (or own) Elgg webservices
 *
 * @since 4.0
 */
class ElggApiClient {
	
	use TimeUsing;
	
	/**
	 * @var string
	 */
	protected $url;
	
	/**
	 * @var array
	 */
	protected $params;
	
	/**
	 * @var string GET|POST
	 */
	protected $method;
	
	/**
	 * @var string
	 */
	protected $public_api_key;
	
	/**
	 * @var string
	 */
	protected $private_api_key;
	
	/**
	 * Create a new API client
	 *
	 * @param string $url    Endpoint url
	 * @param array  $params Parameters for the API call
	 * @param string $method Call method (GET|POST)
	 *
	 * @throws \APIException
	 */
	public function __construct(string $url, array $params = [], string $method = 'GET') {
		$this->url = $url;
		$this->params = $params;
		$this->setMethod($method);
	}
	
	/**
	 * Set the endpoint URL
	 *
	 * @param string $url Endpoint URL
	 *
	 * @return self
	 */
	public function setUrl(string $url) : self {
		$this->url = $url;
		
		return $this;
	}
	
	/**
	 * Return the endpoint URL
	 *
	 * @return string
	 */
	public function getUrl() : string {
		return $this->url;
	}
	
	/**
	 * Set parameters for the API call
	 *
	 * @param array $params the new parameters
	 *
	 * @return self
	 */
	public function setParams(array $params = []) : self {
		$this->params = $params;
		
		return $this;
	}
	
	/**
	 * Return the configured parameters
	 *
	 * @return array
	 */
	public function getParams() : array {
		return $this->params;
	}
	
	/**
	 * Set the calling method
	 *
	 * @param string $method Call method (GET|POST)
	 *
	 * @return self
	 * @throws \APIException
	 */
	public function setMethod(string $method = 'GET') : self {
		$method = strtoupper($method);
		switch ($method) {
			case 'GET':
			case 'POST':
				break;
			default:
				throw new \APIException(elgg_echo('APIException:CallMethodNotImplemented', [$method]));
		}
		
		$this->method = $method;
		
		return $this;
	}
	
	/**
	 * Return the call method
	 *
	 * @return string GET|POST
	 */
	public function getMethod() : string {
		return $this->method;
	}
	
	/**
	 * Set public and private API keys for authorisation
	 *
	 * @param string $public  public API key
	 * @param string $private private API key (needed for HMAC authorisation)
	 *
	 * @return self
	 */
	public function setApiKeys(string $public = '', string $private = '') : self {
		$this->public_api_key = $public;
		$this->private_api_key = $private;
		
		return $this;
	}
	
	/**
	 * Execute a request to the configured API endpoint
	 *
	 * @return string
	 */
	public function executeRequest() {
		
		$this->prepareRequest();
		
		// add Middleware
		$client_options = [];
		
		$handler = new CurlHandler();
		$stack = HandlerStack::create($handler);
		
		if ($this->method === 'POST') {
			$stack->push(Middleware::mapRequest([$this, 'addPostHashHeaders']), 'postHash');
		}
		
		if (!empty($this->public_api_key)) {
			if (!empty($this->private_api_key)) {
				// add HMAC headers
				$stack->push(Middleware::mapRequest([$this, 'addHMACHeaders']), 'hmacHeaders');
			} else {
				// make sure api key is in the params
				if (!isset($this->params['api_key'])) {
					$this->params['api_key'] = $this->public_api_key;
				}
			}
		}
		
		$client_options['handler'] = $stack;
		
		// create client
		$client = new Client($client_options);
		
		if ($this->method === 'POST') {
			$result = $client->post($this->url, [
				'form_params' => $this->params,
			]);
		} else {
			$result = $client->get($this->url, [
				'query' => $this->params,
			]);
		}
		
		return $result->getBody()->getContents();
	}
	
	/**
	 * Add post hash headers to an API request
	 *
	 * @param RequestInterface $request the current request
	 *
	 * @return \Psr\Http\Message\RequestInterface
	 * @internal
	 */
	public function addPostHashHeaders(RequestInterface $request) {
		// calculate post hash
		$posthash = elgg_ws_calculate_posthash($request->getBody()->getContents(), 'sha1');
		
		// add headers
		return $request->withHeader('X-Elgg-posthash', $posthash)
			->withHeader('X-Elgg-posthash-algo', 'sha1');
	}
	
	/**
	 * Add HMAC headers to an API request
	 *
	 * @param RequestInterface $request the current request
	 *
	 * @return \Psr\Http\Message\RequestInterface
	 * @internal
	 */
	public function addHMACHeaders(RequestInterface $request) {
		
		$time = $this->getCurrentTime()->getTimestamp();
		$nonce = uniqid('');
		
		$posthashes = $request->getHeader('X-Elgg-posthash');
		
		$hmac = elgg_ws_calculate_hmac('sha256',
			$time,
			$nonce,
			$this->public_api_key,
			$this->private_api_key,
			$request->getUri()->getQuery(),
			empty($posthashes) ? '' : $posthashes[0]
		);
		
		return $request->withHeader('X-Elgg-apikey', $this->public_api_key)
			->withHeader('X-Elgg-time', $time)
			->withHeader('X-Elgg-nonce', $nonce)
			->withHeader('X-Elgg-hmac-algo', 'sha256')
			->withHeader('X-Elgg-hmac', $hmac);
	}
	
	/**
	 * Make sure a request can be handled by the other (Elgg) side
	 *
	 * @todo remove BC querypart post method in a future release (maybe 5.0)
	 *
	 * @return void
	 */
	protected function prepareRequest() {
		// add query params to the params
		$query_params = parse_url($this->url, PHP_URL_QUERY);
		if (!empty($query_params)) {
			$query_params = explode('&', $query_params);
			foreach ($query_params as $param) {
				list($name, $value) = explode('=', $param);
				
				$this->params[$name] = $value;
			}
			
			$this->url = str_replace($query_params, '', $this->url);
		}
		
		// for BC reasons there needs to be a query part for HMAC authorization, so move method to the query
		if ($this->method === 'POST' && isset($this->params['method'])) {
			$this->url = elgg_http_add_url_query_elements($this->url, [
				'method' => $this->params['method'],
			]);
			
			unset($this->params['method']);
		}
	}
}
