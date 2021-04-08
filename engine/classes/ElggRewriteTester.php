<?php

use Elgg\Filesystem\Directory as ElggDirectory;
use Elgg\Project\Paths;
use Elgg\Http\Request;

/**
 * Elgg RewriteTester.
 * Test if URL rewriting is working.
 */
class ElggRewriteTester {
	protected $webserver;
	protected $serverSupportsRemoteRead;
	protected $rewriteTestPassed;
	protected $htaccessIssue;

	/**
	 * Set the webserver as unknown.
	 */
	public function __construct() {
		$this->webserver = 'unknown';
	}

	/**
	 * Run the rewrite test and return a status array
	 *
	 * @param string $url  URL of rewrite test
	 * @param string $path Obsolete, don't use
	 *
	 * @return array
	 */
	public function run($url, $path = null) {

		$this->webserver = \ElggRewriteTester::guessWebServer();

		$this->rewriteTestPassed = $this->runRewriteTest($url);

		if ($this->rewriteTestPassed === false) {
			if ($this->webserver == 'apache' || $this->webserver == 'unknown') {
				if ($this->createHtaccess($url)) {
					$this->rewriteTestPassed = $this->runRewriteTest($url);
				}
			}
		}

		return $this->returnStatus($url);
	}

	/**
	 * Guess the web server from $_SERVER['SERVER_SOFTWARE']
	 *
	 * @return string
	 */
	public static function guessWebServer() {
		if (empty($_SERVER['SERVER_SOFTWARE'])) {
			return 'unknown';
		}

		$serverString = strtolower($_SERVER['SERVER_SOFTWARE']);
		$possibleServers = ['apache', 'nginx', 'lighttpd', 'iis'];
		foreach ($possibleServers as $server) {
			if (elgg_strpos($serverString, $server) !== false) {
				return $server;
			}
		}
		return 'unknown';
	}

	/**
	 * Guess if url contains subdirectory or not.
	 *
	 * @param string $url Rewrite test URL
	 *
	 * @return string|bool Subdirectory string with beginning and trailing slash or false if were unable to determine subdirectory
	 * or pointing at root of domain already
	 */
	public function guessSubdirectory($url) {
		$elements = parse_url($url);
		if (!is_array($elements) || !isset($elements['path'])) {
			return false;
		}
		
		$subdir = trim(dirname($elements['path']), '/');
		if (!$subdir) {
			return false;
		}
		
		return "/$subdir/";
	}

	/**
	 * Hit the rewrite test URL to determine if the rewrite rules are working
	 *
	 * @param string $url Rewrite test URL
	 *
	 * @return bool
	 */
	public function runRewriteTest($url) {
		$this->serverSupportsRemoteRead = ($this->fetchUrl($url) === Request::REWRITE_TEST_OUTPUT);
		return $this->serverSupportsRemoteRead;
	}
	
	/**
	 * Check whether the site homepage can be fetched via curl
	 *
	 * @return boolean
	 */
	public function runLocalhostAccessTest() {
		return (bool) $this->fetchUrl(_elgg_services()->config->wwwroot);
	}

	/**
	 * Fetch a URL
	 *
	 * @param string $url The URL
	 *
	 * @return string Note that empty string may imply failure in fetching or empty response
	 */
	private function fetchUrl($url) {
		$response = '';

		if (ini_get('allow_url_fopen')) {
			$ctx = stream_context_create([
				'http' => [
					'follow_location' => 0,
					'timeout' => 5,
				],
			]);
			$response = @file_get_contents($url, false, $ctx);
		}

		if (!$response && function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch);
		}

		return (string) $response;
	}

	/**
	 * Create Elgg's .htaccess file or confirm that it exists
	 *
	 * @param string $url URL of rewrite test
	 *
	 * @return bool
	 */
	public function createHtaccess($url) {
		$root = ElggDirectory\Local::projectRoot();
		$file = $root->getFile(".htaccess");

		if ($file->exists()) {
			// check that this is the Elgg .htaccess
			$data = $file->getContents();
			if (empty($data)) {
				// don't have permission to read the file
				$this->htaccessIssue = 'read_permission';
				return false;
			}

			if (elgg_strpos($data, 'Elgg') === false) {
				$this->htaccessIssue = 'non_elgg_htaccess';
				return false;
			}

			// check if this is an old Elgg htaccess
			if (elgg_strpos($data, 'RewriteRule ^rewrite.php$ install.php') === false) {
				$this->htaccessIssue = 'old_elgg_htaccess';
				return false;
			}
			return true;
		}

		if (!is_writable($root->getPath())) {
			$this->htaccessIssue = 'write_permission';
			return false;
		}

		// create the .htaccess file
		$result = copy(Paths::elgg() . "install/config/htaccess.dist", $file->getPath());
		if (!$result) {
			$this->htaccessIssue = 'cannot_copy';
			return false;
		}
		
		// does default RewriteBase work already?
		if (!$this->runRewriteTest($url)) {
			//try to rewrite to guessed subdirectory
			if ($subdir = $this->guessSubdirectory($url)) {
				$contents = $file->getContents();
				$contents = preg_replace("/#RewriteBase \/(\r?\n)/", "RewriteBase $subdir\$1", $contents);
				if ($contents) {
					$file->putContents($contents);
				}
			}
		}

		return true;
	}

	/**
	 * Create the status array required by the ElggInstaller
	 *
	 * @param string $url Rewrite test URL
	 *
	 * @return array
	 */
	protected function returnStatus($url) {
		if ($this->rewriteTestPassed) {
			return [
				'severity' => 'success',
				'message' => _elgg_services()->translator->translate('install:check:rewrite:success'),
			];
		}

		if ($this->serverSupportsRemoteRead == false) {
			$msg = _elgg_services()->translator->translate('install:warning:rewrite:unknown', [$url]);
			$msg .= elgg_view('install/js_rewrite_check', ['url' => $url]);
			
			return [
				'severity' => 'warning',
				'message' => $msg,
			];
		}

		if ($this->webserver == 'apache') {
			$serverString = _elgg_services()->translator->translate('install:error:rewrite:apache');
			$msg = "$serverString\n\n";
			if (!isset($this->htaccessIssue)) {
				$msg .= _elgg_services()->translator->translate('install:error:rewrite:allowoverride');
				$msg .= elgg_view('install/js_rewrite_check', ['url' => $url]);
			
				return [
					'severity' => 'warning',
					'message' => $msg,
				];
			}
			$msg .= _elgg_services()->translator->translate("install:error:rewrite:htaccess:{$this->htaccessIssue}");
			return [
				'severity' => 'warning',
				'message' => $msg,
			];
		}

		if ($this->webserver != 'unknown') {
			$serverString = _elgg_services()->translator->translate("install:error:rewrite:{$this->webserver}");
			$msg = "$serverString\n\n";
			$msg .= _elgg_services()->translator->translate("install:error:rewrite:altserver");
			return [
				'severity' => 'warning',
				'message' => $msg,
			];
		}

		return [
			'severity' => 'warning',
			'message' => _elgg_services()->translator->translate('install:error:rewrite:unknown'),
		];
	}
}
