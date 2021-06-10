<?php

namespace Elgg\I18n;

/**
 * Removes invalid language files from an installation
 *
 * @internal
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

		if (is_dir("$dir/install/languages")) {
			$this->cleanLanguagesDir("$dir/install/languages");
		}

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
				$code = $this->normalizeLanguageCode($code);

				if (in_array($code, $this->codes)) {
					// rename file to lowercase
					rename($path, "$dir/$code.php");
					$this->log[] = "Renamed $path to $code.php";
				} else {
					unlink($path);
					$this->log[] = "Removed $path";
				}
			}
			
			if ($code !== 'en' && file_exists("{$dir}/{$code}.php")) {
				$this->cleanupEmptyTranslations("{$dir}/{$code}.php");
			}
		}
	}
	
	/**
	 * Remove empty translations from a translation file
	 *
	 * @param string $translation_file path to the translation file
	 *
	 * @return void
	 */
	public function cleanupEmptyTranslations(string $translation_file): void {
		$contents = file_get_contents($translation_file);
		if (empty($contents)) {
			return;
		}
		
		$pattern = '/^\s*[\'"].*[\'"] => [\'"]{2},{0,1}\R/m';
		$count = 0;
		$contents = preg_replace($pattern, '', $contents, -1, $count);
		
		if ($count > 0) {
			// something was changed
			file_put_contents($translation_file, $contents);
			
			$this->log[] = "Cleanen empty translations from {$translation_file}";
		}
	}
	
	/**
	 * Normalize a language code (e.g. from Transifex)
	 *
	 * @param string $code Language code
	 *
	 * @return string
	 *
	 * @internal
	 */
	protected function normalizeLanguageCode(string $code) {
		$code = strtolower($code);
		$code = preg_replace('~[^a-z0-9]~', '_', $code);
		return $code;
	}
}
