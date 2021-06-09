<?php

namespace Elgg\Assets;

use Elgg\Cache\SystemCache;
use Elgg\Config;
use Elgg\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;

/**
 * Fetch external images server side
 *
 * @internal
 */
class ImageFetcherService {

	protected const CACHE_PREFIX = 'image_fetcher_';

	/**
	 * @var SystemCache
	 */
	protected $cache;
	
	/**
	 * @var \GuzzleHttp\Client
	 */
	protected $client;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * Constructor
	 *
	 * @param Config       $config  config
	 * @param SystemCache  $cache   the system cache
	 * @param \ElggSession $session the current session
	 */
	public function __construct(Config $config, SystemCache $cache, \ElggSession $session) {
		$this->config = $config;
		$this->cache = $cache;
		$this->session = $session;
		
		$proxy_config = $this->config->proxy ?? [];
		
		$options = [
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
			
			$options[RequestOptions::PROXY] = $host;
		}
		
		$this->client = new Client($options);
	}
	
	/**
	 * Get an image
	 *
	 * @param string $image_url the image url to get
	 *
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 *
	 * @return false|array result contains
	 * 	- data: the image data
	 * 	- content-type: the content type of the image
	 * 	- name: the name of the image
	 */
	public function getImage(string $image_url) {
		if (empty($image_url)) {
			throw new InvalidArgumentException('a non-empty image url is required for image fetching');
		}
		
		$image_url = htmlspecialchars_decode($image_url);
		$image_url = elgg_normalize_url($image_url);
		
		$cache = $this->loadFromCache($image_url);
		if (!empty($cache)) {
			return $cache;
		}
		
		$site = elgg_get_site_entity();
		$options = [];
		
		if (stripos($image_url, $site->getURL()) === 0) {
			// internal url, can use session cookie
			$cookie_config = $this->config->getCookieConfig();
			
			$cookies = [
				$cookie_config['session']['name'] => $this->session->getID(),
			];
			
			$domain = $cookie_config['session']['domain'] ?: $site->getDomain();
			
			$cookiejar = CookieJar::fromArray($cookies, $domain);
			$options[RequestOptions::COOKIES] = $cookiejar;
		}
		
		try {
			$response = $this->client->get($image_url, $options);
		} catch (TransferException $e) {
			// this shouldn't happen, but just in case
			return false;
		}
		
		if ($response->getStatusCode() !== ELGG_HTTP_OK) {
			return false;
		}
		
		$result = [
			'data' => $response->getBody()->getContents(),
			'content-type' => $response->getHeaderLine('content-type') ?: 'application/octet-stream',
			'name' => basename($image_url),
		];
		
		$this->saveToCache($image_url, $result);
		
		return $result;
	}
	
	/**
	 * Load an image url from cache
	 *
	 * @param string $image_url the url to load
	 *
	 * @return array
	 */
	protected function loadFromCache(string $image_url): array {
		$cache = $this->cache->load(self::CACHE_PREFIX . md5($image_url));
		
		return is_array($cache) ? $cache : [];
	}
	
	/**
	 * Save image data in system cache for easy reuse
	 *
	 * @param string $image_url the image url
	 * @param array  $data      the image data
	 *
	 * @return bool
	 */
	protected function saveToCache(string $image_url, array $data): bool {
		return $this->cache->save(self::CACHE_PREFIX . md5($image_url), $data);
	}
}
