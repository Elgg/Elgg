<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.9.0
 *
 * @todo keep track of what files have been loaded otherwise we are loading the
 * same language files again and again
 */
class Elgg_I18n_TranslationLoader {

	/** @var array */
	protected $directories = array();

	/** @var ElggCache */
	protected $cache;

	/**
	 * Create the translation loader
	 *
	 * @param ElggCache $cache Cache for translations
	 */
	public function __construct(ElggCache $cache = null) {
		$this->cache = $cache;
	}

	/**
	 * Add a translation directory
	 *
	 * @param string $directory Directory path that has translations
	 * @return void
	 */
	public function addDirectory($directory) {
		$this->directories[] = rtrim($directory, '/\\');
	}

	/**
	 * Get all the translation directory paths
	 *
	 * @return array
	 */
	public function getDirectories() {
		return $this->directories;
	}

	/**
	 * Load a translation
	 *
	 * @param string $language Two letter language code
	 * @return array
	 */
	public function loadTranslation($language) {
		$translation = array();
		foreach ($this->directories as $dir) {
			$language_file = "$dir/$language.php";
			if (is_readable($language_file)) {
				$result = include($language_file);
				if ($result) {
					if (is_array($result)) {
						// Elgg 1.9 style language file
						$translation = array_merge($translation, $result);
					} else {
						// Elgg 1.0-1.8 style (add_translation)
					}
				} else {
					// why would this fail
				}
			}
		}

		return $translation;
	}

	/**
	 * Get all languages available
	 *
	 * @return array
	 */
	public function getAllLanguages() {
		$languages = array();
		foreach ($this->directories as $dir) {
			$handle = opendir($dir);
			if (!$handle) {
				// log error or throw exception
				continue;
			}

			while (false !== ($file = readdir($handle))) {
				if (substr($file, 0, 1) == '.' || substr($file, -4) !== '.php'
						|| strlen($file) != 6) {
					// skip non-language files
					continue;
				}

				$languages[] = substr($file, 0, 2);
			}
		}

		return array_unique($languages);
	}

}
