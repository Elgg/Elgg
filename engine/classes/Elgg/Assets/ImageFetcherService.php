<?php

namespace Elgg\Assets;

use Elgg\Cache\SystemCache;
use Elgg\Config;
use Elgg\Exceptions\ExceptionInterface;
use Elgg\Exceptions\InvalidArgumentException;
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
	 * @var \Elgg\Http\Client
	 */
	protected $client;
	
	/**
	 * Constructor
	 *
	 * @param Config       $config  config
	 * @param SystemCache  $cache   the system cache
	 * @param \ElggSession $session the current session
	 */
	public function __construct(
		protected Config $config,
		protected SystemCache $cache,
		protected \ElggSession $session
	) {
		$this->client = elgg_get_http_client();
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
		
		$cache_key = $this->makeCacheKey($image_url);
		$cache = $this->cache->load($cache_key);
		if (isset($cache)) {
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
			$this->cache->save($cache_key, false);
			return false;
		}
		
		if ($response->getStatusCode() !== ELGG_HTTP_OK) {
			$this->cache->save($cache_key, false);
			return false;
		}
		
		$image_contents = $response->getBody()->getContents();
		if (!$this->validateImageData($image_contents)) {
			$this->cache->save($cache_key, false);
			return false;
		}
		
		$result = [
			'data' => $image_contents,
			'content-type' => $response->getHeaderLine('content-type') ?: 'application/octet-stream',
			'name' => basename($image_url),
		];
		
		$this->cache->save($cache_key, $result);
		
		return $result;
	}
	
	/**
	 * Get the cache key for a given url
	 *
	 * @param string $image_url the image url
	 *
	 * @return string
	 */
	protected function makeCacheKey(string $image_url): string {
		return self::CACHE_PREFIX . md5($image_url);
	}
	
	/**
	 * Validate that the fetched image data is an actual image
	 *
	 * @param string $image_data image fetch result
	 *
	 * @return bool
	 */
	protected function validateImageData(string $image_data): bool {
		if (empty($image_data)) {
			return false;
		}
		
		$tmp = new \ElggTempFile();
		try {
			$tmp->open('write');
			$tmp->write($image_data);
			$tmp->close();
		} catch (ExceptionInterface $e) {
			// file error
			return false;
		}
		
		$info = getimagesize($tmp->getFilenameOnFilestore());
		
		return is_array($info);
	}
}
