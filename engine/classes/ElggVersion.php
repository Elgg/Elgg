<?php
/**
 * Class providing ability to check local Elgg version/releace and to get and compare newest release
 * at http://elgg.org
 * @see https://elgg.org/
 * @todo consider moving version.php contents to tihs class as constants 
 */
final class ElggVersion {

	/**
	 * @var string
	 */
	private static $localVersion;

	/**
	 * @var string
	 */
	private static $localRelease;

	/**
	 * @var string
	 */
	private static $latestRelease;
	
	/**
	 * Time in seconds to store latest version in datalist before running external check.
	 * @var int
	 */
	private static $cachingPeriod = 86400;

	/**
	 * Get the current Elgg version information
	 *
	 * @param bool $humanreadable Whether to return a human readable version (default: false)
	 *
	 * @return string|false Depending on success
	 */
	static function getVersion($humanreadable = false) {
		global $CONFIG;

		if (isset($CONFIG->path)) {
			if (!isset(self::$localVersion) || !isset(self::$localRelease)) {
				if (!include($CONFIG->path . "version.php")) {
					return false;
				}
				self::$localVersion = $version;
				self::$localRelease = $release;
			}
			return (!$humanreadable) ? self::$localVersion : self::$localRelease;
		}

		return false;
	}

	/**
	 * Compares two elgg release strings
	 * @param string $a first release
	 * @param string $b second release
	 * @return int -1 if the first version is lower than the second, 0 if they are equal, and 1 if the second is lower
	 */
	static function compareReleases($a, $b) {
		return version_compare($a, $b);
	}
	
	/**
	 * Tries to fetch contents of the URL.
	 * @param string $url URL to fetch
	 * @return string|boolean returns response contents on success or false on failure
	 */
	private static function getUrl($url) {
		//try url fopen
		$file = fopen($url, 'r');
		if ($file!==false) {
			$result = stream_get_contents($file);
			fclose($file);
			return $result;
		}
		//try curl
		if (extension_loaded('curl')) {
			$ch = curl_init($url);
			curl_setopt_array($ch, array(
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
			));
			$result = curl_exec($ch);
			curl_close($ch);
			if ($result!==false) {
				return $result;
			}
		}
		return false;
	}
	
	/**
	 * Fetch latest Elgg release from Github tags via Github API
	 * @return string|boolean returns release string or false on failure
	 */
	private static function getLatestFromGithub() {
		$release = false;
		$contents = self::getUrl('https://api.github.com/repos/Elgg/Elgg/git/refs/tags/');
		if ($contents===false) {
			return false;
		}
		$data = json_decode($contents);
		if ($data!==null) {
			foreach ($data as $e) {
				$r = substr($e->ref, strlen('refs/tags/'));
				if (!$release || (self::compareReleases($r, $release) > 0)) {
					$release = $r;
				}
			}
		}
		return $release;
	}
	
	/**
	 * Fetch latest Elgg release from elgg.org main page download link label.
	 * @return string|boolean returns release string or false on failure
	 */
	private static function getLatestFromElggOrg() {
		$release = false;
		$contents = self::getUrl('http://elgg.org/');
		if ($contents!==false) {
			$regExp = '#<a\s+[^>]*href="download\.php"[^>]*\s+class="download"[^>]*>[^0-9<]*([0-9\.]+)\s*</a>#m';
			if (preg_match($regExp, $contents, $matches)) {
				$release = $matches[1];
				if ($release) {
					return $release;
				}
			}
		}
		return false;
	}
	
	/**
	 * Tries several methods and sources of determining latest Elgg release.
	 * @return string|boolean returns release string or false on failure
	 */
	static function getLatestRelease($overrideCache = false) {
		if (self::$latestRelease!==null) {
			return self::$latestRelease;
		}
		//fetch from datalist if caching period not reached
		$lastChecked = datalist_get('version_last_checked');
		$cachedRelease = datalist_get('version_newest');
		$time = time();
		if ($cachedRelease && !$overrideCache && $lastChecked + self::$cachingPeriod > $time) {
			self::$latestRelease = $cachedRelease;
			return $cachedRelease;
		}
		
		$release = false;
		$mt = microtime(true);
		if (!($release = self::getLatestFromGithub())) {
			// github is wrong, maybe missing https support, try elgg.org
			$release = self::getLatestFromElggOrg();
		}
		datalist_set('version_last_checked', $time);
		datalist_set('version_newest', $release);
		self::$latestRelease = $release;
		return $release;
	}

	/**
	 * @return null|int timestamp representing last check for external version date 
	 * or null if never checked
	 */
	static function getLatestReleaseLastChecked() {
		return datalist_get('version_last_checked');
	}
	
	/**
	 * Checks if provided version is the same (or newer) than the latest one.
	 * @param string $version version to check, gets local core version by default
	 * @throws IOException
	 * @return boolean
	 */
	static function isLatestRelease($version = null) {
		if ($version===null) {
			$version = self::getVersion(true);
		}
		$latest = self::getLatestRelease();
		if ($latest===false) {
			throw new IOException(elgg_echo('IOException:UnknownVersion'));
		}
		return self::compareReleases($latest, $version) < 1;
	}
}

