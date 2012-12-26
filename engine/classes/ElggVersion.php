<?php
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

	static function compareReleases($a, $b) {
		return version_compare($a, $b, '>');
	}

	static function getLatest() {
		//requires open ssl?? - that sucks
		$url = 'https://api.github.com/repos/Elgg/Elgg/git/refs/tags/';
		//requires url fopen
		$file = fopen($url, 'r');
		$contents = stream_get_contents($file);
		$data = json_decode($contents);

		$data = array_map(function($e) {
			$val = $e->ref;
			return substr($val, strlen('refs/tags/'));
		}, $data);
		$version = array_reduce($data, function($a, $b) {
			return ElggVersion::compareReleases($a, $b) ? $a : $b;
		});

		return $version;
	}

	static function isLatest() {
		$local = self::getVersion(true);
		return self::compareReleases(self::getLatest(), $local) < 1;
	}
}

//var_dump(ElggVersion::getLatest());
//var_dump(ElggVersion::isLatest());
