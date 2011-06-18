<?php

/**
 * Elgg RewriteTester.
 * Test if URL rewriting is working.
 *
 * @package    Elgg.Core
 * @subpackage Installer
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
	 * @param string $path Root directory of Elgg with trailing slash
	 *
	 * @return array
	 */
	public function run($url, $path) {

		$this->webserver = ElggRewriteTester::guessWebServer();

		$this->rewriteTestPassed = $this->runRewriteTest($url);

		if ($this->rewriteTestPassed == FALSE) {
			if ($this->webserver == 'apache' || $this->webserver == 'unknown') {
				if ($this->createHtaccess($path)) {
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
		$serverString = strtolower($_SERVER['SERVER_SOFTWARE']);
		$possibleServers = array('apache', 'nginx', 'lighttpd', 'iis');
		foreach ($possibleServers as $server) {
			if (strpos($serverString, $server) !== FALSE) {
				return $server;
			}
		}
		return 'unknown';
	}

	/**
	 * Hit the rewrite test URL to determine if the rewrite rules are working
	 *
	 * @param string $url Rewrite test URL
	 *
	 * @return bool
	 */
	protected function runRewriteTest($url) {

		$this->serverSupportsRemoteRead = TRUE;

		if (function_exists('curl_init')) {
			// try curl if installed
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch);
			return $response === 'success';
		} else if (ini_get('allow_url_fopen')) {
			// use file_get_contents as fallback
			$response = file_get_contents($url);
			return $response === 'success';
		} else {
			$this->serverSupportsRemoteRead = FALSE;
			return FALSE;
		}
	}

	/**
	 * Create Elgg's .htaccess file or confirm that it exists
	 *
	 * @param string $path Elgg's root directory with trailing slash
	 *
	 * @return bool
	 */
	public function createHtaccess($path) {
		$filename = "{$path}.htaccess";
		if (file_exists($filename)) {
			// check that this is the Elgg .htaccess
			$data = file_get_contents($filename);
			if ($data === FALSE) {
				// don't have permission to read the file
				$this->htaccessIssue = 'read_permission';
				return FALSE;
			}
			if (strpos($data, 'Elgg') === FALSE) {
				$this->htaccessIssue = 'non_elgg_htaccess';
				return FALSE;
			} else {
				// check if this is an old Elgg htaccess
				if (strpos($data, 'RewriteRule ^rewrite.php$ install.php') == FALSE) {
					$this->htaccessIssue = 'old_elgg_htaccess';
					return FALSE;
				}
				return TRUE;
			}
		}

		if (!is_writable($path)) {
			$this->htaccessIssue = 'write_permission';
			return FALSE;
		}

		// create the .htaccess file
		$result = copy("{$path}htaccess_dist", $filename);
		if (!$result) {
			$this->htaccessIssue = 'cannot_copy';
			return FALSE;
		}

		return TRUE;
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
			return array(
				'severity' => 'pass',
				'message' => elgg_echo('install:check:rewrite:success'),
			);
		}

		if ($this->serverSupportsRemoteRead == FALSE) {
			$msg = elgg_echo('install:warning:rewrite:unknown', array($url));
			return array(
				'severity' => 'warning',
				'message' => $msg,
			);
		}

		if ($this->webserver == 'apache') {
			$serverString = elgg_echo('install:error:rewrite:apache');
			$msg = "$serverString\n\n";
			if (!isset($this->htaccessIssue)) {
				$msg .= elgg_echo('install:error:rewrite:allowoverride');
				return array(
					'severity' => 'failure',
					'message' => $msg,
				);
			}
			$msg .= elgg_echo("install:error:rewrite:htaccess:{$this->htaccessIssue}");
			return array(
				'severity' => 'failure',
				'message' => $msg,
			);
		}

		if ($this->webserver != 'unknown') {
			$serverString = elgg_echo("install:error:rewrite:{$this->webserver}");
			$msg = "$serverString\n\n";
			$msg .= elgg_echo("install:error:rewrite:altserver");
			return array(
				'severity' => 'failure',
				'message' => $msg,
			);
		}

		return array(
			'severity' => 'failure',
			'message' => elgg_echo('install:error:rewrite:unknown'),
		);
	}
}