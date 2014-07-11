<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.9.0
 */
class Elgg_I18n_TranslationLoader {

	/** @var array */
	protected $directories = array();

	/** @var ElggCache */
	protected $cache;

	/** @var array */
	protected $uncachedTranslations = array();

	/** @var array */
	protected $localTranslationCache = array();

	/**
	 * Create the translation loader
	 *
	 * @param ElggCache $cache Cache for translations
	 */
	public function __construct(ElggCache $cache = null) {
		$this->cache = $cache;
	}

	/**
	 * Caches translations when appriopiate
	 */
	public function __destruct() {
		if ($this->cache && $this->uncachedTranslations) {
			foreach ($this->uncachedTranslations as $language => $translation) {
				$this->cache->setVariable("$language.lang", serialize($translation));
			}
		}
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
	 * @return array Array of key => translation
	 */
	public function loadTranslation($language) {
		$translation = array();

		if ($this->cache) {
			$data = $this->cache->getVariable("$language.lang");
			if ($data) {
				$translation = unserialize($data);
			}
		}

		if (!$translation) {
			foreach ($this->directories as $dir) {
				$language_file = "$dir/$language.php";
				if ($this->isCached($language_file)) {
					$translation = array_merge($translation, $this->getCache($language_file));
				} else {
					if (is_readable($language_file)) {
						$result = include($language_file);
						if ($result) {
							if (is_array($result)) {
								// Elgg 1.9 style language file
								$this->setCache($language_file, $result);
								$translation = array_merge($translation, $result);
							} else {
								// Elgg 1.0-1.8 style (add_translation)
							}
						} else {
							// why would this fail
						}
					}
				}
			}

			if ($this->cache) {
				$this->uncachedTranslations[$language] = $translation;
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

	/**
	 * Is this translation file cached
	 *
	 * @param string $file Translation file
	 * @return bool
	 */
	protected function isCached($file) {
		return isset($this->localTranslationCache[$file]);
	}

	/**
	 * Set the cached translation for this file
	 *
	 * @param string $file  Translation file
	 * @param array  $value Value to cache
	 * @return void
	 */
	protected function setCache($file, $value) {
		$this->localTranslationCache[$file] = $value;
	}

	/**
	 * Get the cached translation for this file
	 *
	 * @param string $file Translation file
	 * @return array
	 */
	protected function getCache($file) {
		return $this->localTranslationCache[$file];
	}

}
