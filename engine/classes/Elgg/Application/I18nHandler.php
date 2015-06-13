<?php
namespace Elgg\Application;

use Elgg\Application;
use Elgg\LanguagePacks\Config;
use Stash\Driver\FileSystem;
use Stash\Pool;
use Elgg\LanguagePacks\Splitter;
use Elgg\I18n\Translator;

/**
 * Language pack handler
 *
 * Handles URL paths:
 * /_i18n/<ts>/<lang>/pack/<pack>.js
 * /_i18n/<ts>/<lang>/key/<key_URL_encoded>.js
 *
 * @access private
 *
 * @package Elgg.Core
 */
class I18nHandler {

	/**
	 * Path of cache dir from dataroot. We assume that toggling/clearing simplecache will
	 * clear this directory. If we change caching strategy (e.g. APC/memcache) we'll need
	 * to make sure these actions flush the cache.
	 */
	const CACHE_PATH = 'views_simplecache/i18n';

	/**
	 * TTL used if simplecache is off. It's too costly to build the language file to throw
	 * it away on every request.
	 */
	const SHORT_TTL = 10;

	const LOCK_TTL = 3;

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var string
	 */
	private $dataroot;

	/**
	 * @var string
	 */
	private $simplecache_enabled;

	/**
	 * Constructor
	 *
	 * @param Application $app Elgg Application
	 */
	public function __construct(Application $app) {
		$this->application = $app;
	}

	/**
	 * Handle a request for a cached language resource
	 *
	 * @param array $path        URL path
	 * @param array $server_vars Server vars
	 * @return void
	 */
	public function handleRequest($path, $server_vars) {
		$request = $this->parsePath($path);
		if (!$request) {
			$this->send403();
		}

		$ts = $request['ts'];
		$lang = $request['lang'];
		$mode = $request['mode'];

		header('Content-Type: text/javascript', true);

		// this may/may not have to connect to the DB
		$this->setupSimplecache();

		$etag = "\"$ts\"";
		// If is the same ETag, content didn't change.
		if (isset($server_vars['HTTP_IF_NONE_MATCH']) && trim($server_vars['HTTP_IF_NONE_MATCH']) === $etag) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		if ($ts > 0) {
			$this->sendCacheHeaders($etag);
		}

		if (!in_array($lang, Translator::getAllLanguageCodes())) {
			$this->send403();
		}

		if ($mode === 'pack') {
			echo $this->getPack($request['pack'], $lang);
		} else {
			echo $this->getKey($request['key'], $lang);
		}
		exit;
	}

	/**
	 * Parse a request
	 *
	 * @param string $path Request URL path
	 * @return array Cache parameters (empty array if failure)
	 */
	public function parsePath($path) {

		$ret = [];

		// /_i18n/<ts>/<lang>/pack/<pack>.js
		// /_i18n/<ts>/<lang>/key/<key_URL_encoded>.js
		if (!preg_match('#^/_i18n/([0-9]+)/([a-zA-Z_]+)/(.*)\.js$#', $path, $matches)) {
			return [];
		}

		$ret['ts'] = $matches[1];
		$ret['lang'] = $matches[2];
		$rest = $matches[3];

		if (preg_match('#^pack/([a-zA-Z]+)$#', $rest, $matches)) {
			$ret['pack'] = $matches[1];
			$ret['mode'] = 'pack';
			return $ret;
		}

		if (preg_match('#^key/(.*)$#', $rest, $matches)) {
			$ret['key'] = rawurldecode($matches[1]);
			$ret['mode'] = 'key';
			return $ret;
		}

		return [];
	}

	/**
	 * Do a minimal engine load
	 *
	 * @return void
	 */
	protected function setupSimplecache() {
		// we can't use Elgg\Config::get yet. It fails before the core is booted
		$config = $this->application->config;
		$config->loadSettingsFile();

		$path = $config->getVolatile('dataroot');
		$is_enabled = $config->getVolatile('simplecache_enabled');

		if ($path && $is_enabled !== null) {
			$this->dataroot = $path;
			$this->simplecache_enabled = $is_enabled;
			return;
		}

		$db = $this->application->getDb();

		try {
			$rows = $db->getData("
				SELECT `name`, `value`
				FROM {$db->getTablePrefix()}datalists
				WHERE `name` IN ('dataroot', 'simplecache_enabled')
			");
			if (!$rows) {
				$this->send403('Cache error: unable to get the data root');
			}
		} catch (\DatabaseException $e) {
			if (0 === strpos($e->getMessage(), "Elgg couldn't connect")) {
				$this->send403('Cache error: unable to connect to database server');
			} else {
				$this->send403('Cache error: unable to connect to Elgg database');
			}
			exit; // unnecessary, but helps PhpStorm understand
		}

		foreach ($rows as $row) {
			if ($row->name === 'dataroot') {
				$row->value = rtrim($row->value, '/\\') . DIRECTORY_SEPARATOR;
			}
			$config->set($row->name, $row->value);
		}

		$this->dataroot = $config->getVolatile('dataroot');
		$this->simplecache_enabled = $config->getVolatile('simplecache_enabled');
	}

	/**
	 * Send cache headers
	 *
	 * @param string $etag ETag value
	 * @return void
	 */
	protected function sendCacheHeaders($etag) {
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("ETag: $etag");
	}

	protected function getKey($key, $language) {
		$all_translations = $this->getAllTranslations($language);

		$value = isset($all_translations[$key]) ? $all_translations[$key] : null;

		return json_encode($value);
	}

	protected function getPack($name, $language) {
		$packs_config = $this->getPacksConfig();
		if (empty($packs_config[$name])) {
			return "{}";
		}

		$ttl = $this->simplecache_enabled ? (86400 * 365) : self::SHORT_TTL;

		$item = $this->getCache()->getItem("$language/$name");
		$pack = $item->get();

		if ($item->isMiss()) {
			$item->lock(self::LOCK_TTL);

			$all_translations = $this->getAllTranslations($language);

			$splitter = new Splitter($all_translations, $packs_config);
			$translations = $splitter->getPack($name);

			$pack = json_encode($translations);

			$item->set($pack, $ttl);
		}

		return $pack;
	}

	protected function getAllTranslations($language) {
		// piggyback off system cache if available
		$lang_file = "{$this->dataroot}system_cache/$language.lang";
		if (is_file($lang_file)) {
			return unserialize(file_get_contents($lang_file));
		}

		$ttl = $this->simplecache_enabled ? (86400 * 365) : self::SHORT_TTL;

		$item = $this->getCache()->getItem($language);
		$translations = $item->get();

		if ($item->isMiss()) {
			$item->lock(self::LOCK_TTL);
			$this->application->bootCore();

			$all_translations = $this->application->config->get('translations');

			if ($language !== 'en' && !isset($all_translations[$language])) {
				// try to reload missing translations
				reload_all_translations();
				$all_translations = $this->application->config->get('translations');
			}

			$translations = $all_translations['en'];

			if ($language !== 'en' && isset($all_translations[$language])) {
				$translations = array_merge($translations, $all_translations[$language]);
			}

			$item->set($translations, $ttl);
		}

		return $translations;
	}

	/**
	 * @return Config
	 */
	protected function getPacksConfig() {
		$ttl = $this->simplecache_enabled ? (86400 * 365) : self::SHORT_TTL;

		$item = $this->getCache()->getItem("_config");
		$config = $item->get();

		if ($item->isMiss()) {
			$item->lock(self::LOCK_TTL);

			$this->application->bootCore();
			$config = _elgg_services()->languagePacks->buildConfig();

			$item->set($config, $ttl);
		}

		return $config;
	}

	/**
	 * Get the file cache
	 *
	 * @return Pool
	 */
	protected function getCache() {
		static $pool;
		if ($pool === null) {
			$dir_name = $this->dataroot . self::CACHE_PATH;
			if (!is_dir($dir_name)) {
				mkdir($dir_name, 0700, true);
			}

			$driver = new FileSystem();
			$driver->setOptions([
				'dirSplit' => 1,
				'path' => $dir_name,
			]);
			$pool = new Pool($driver);
		}
		return $pool;
	}

	/**
	 * Send an error message to requestor
	 *
	 * @param string $msg Optional message text
	 * @return void
	 */
	protected function send403($msg = 'Cache error: bad request') {
		header('HTTP/1.1 403 Forbidden');
		echo $msg;
		exit;
	}
}
