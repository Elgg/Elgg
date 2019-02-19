<?php
namespace Elgg\I18n;

/**
 * Removes invalid language files from an installation
 *
 * @access private
 */
class ReleaseCleaner {

	/**
	 * @var string[]
	 */
	private $codes;

	/**
	 * @var string[]
	 */
	public $log = [];

	/**
	 * Constructor
	 *
	 * @param string[] $codes Valid language codes
	 */
	public function __construct(array $codes = []) {
		if (!$codes) {
			$codes = elgg()->locale->getLanguageCodes();
		}
		$this->codes = $codes;
	}

	/**
	 * Clean up within an installation
	 *
	 * @param string $dir The installation dir
	 *
	 * @return void
	 */
	public function cleanInstallation($dir) {
		$dir = rtrim($dir, '/\\');

		if (is_dir("$dir/languages")) {
			$this->cleanLanguagesDir("$dir/languages");
		}

		$dir = "$dir/mod";

		foreach (scandir($dir) as $entry) {
			if ($entry[0] === '.') {
				continue;
			}

			$path = "$dir/$entry";

			if (is_dir("$path/languages")) {
				$this->cleanLanguagesDir("$path/languages");
			}
		}
	}

	/**
	 * Clean up a languages dir
	 *
	 * @param string $dir Languages dir
	 *
	 * @return void
	 */
	public function cleanLanguagesDir($dir) {
		$dir = rtrim($dir, '/\\');

		foreach (scandir($dir) as $entry) {
			if ($entry[0] === '.') {
				continue;
			}

			if (pathinfo($entry, PATHINFO_EXTENSION) !== 'php') {
				continue;
			}

			$path = "$dir/$entry";

			$code = basename($entry, '.php');
			if (!in_array($code, $this->codes)) {
				$code = Translator::normalizeLanguageCode($code);

				if (in_array($code, $this->codes)) {
					// rename file to lowercase
					rename($path, "$dir/$code.php");
					$this->log[] = "Renamed $path to $code.php";
					continue;
				}

				unlink($path);
				$this->log[] = "Removed $path";
			}
		}
	}
}
