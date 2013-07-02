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
 * same language files again and again when the cache is off
 */
class Elgg_I18n_TranslationsService {

	/** @var ElggCache */
	protected $cache;

	/** @var string */
	protected $siteLanguage;

	/** @var string */
	protected $userLanguage;

	/** @var array */
	protected $translationDirectories = array();

	/** @var array */
	protected $translations = array();

	/** @var array */
	protected $translators = array();

	/**
	 * Create a translation service
	 * 
	 * @param string    $directory The directory for the core language files
	 * @param string    $language  Two letter language code for default language
	 * @param ElggCache $cache
	 */
	public function __construct($directory, $language = 'en', $cache = null) {
		$this->siteLanguage = $language;
		$this->userLanguage = $language;
		$this->cache = $cache;
		$this->registerTranslationDirectory($directory);
	}

	/**
	 * Set the site language
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function setSiteLanguage($language) {
		$this->assertValidLanguage($language);
		$this->siteLanguage = $language;
	}

	/**
	 * Get the site language
	 * 
	 * @return string
	 */
	public function getSiteLanguage() {
		return $this->siteLanguage;
	}

	/**
	 * Set the user's language
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function setUserLanguage($language) {
		$this->assertValidLanguage($language);
		$this->userLanguage = $language;
	}

	/**
	 * Get the user's language
	 * 
	 * @return string
	 */
	public function getUserLanguage() {
		return $this->userLanguage;
	}

	/**
	 * Get the translation of a message key 
	 *
	 * @param string $key      The message key
	 * @param array  $args     Optional array of arguments to pass through vsprintf()
	 * @param string $language Optional 2 letter language code to override default language
	 * @return string
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function translate($key, $args = array(), $language = '') {
		$this->loadTranslation($this->userLanguage);
		$this->loadTranslation($this->siteLanguage);
		if ($language !== '') {
			$this->assertValidLanguage($language);
			$this->loadTranslation($language);			
		}

		$string = $this->translators[$language]->translate($key, $args);
		if (!$string) {
			$string = $this->translators[$this->userLanguage]->translate($key, $args);
		}
		if (!$string) {
			$string = $this->translators[$this->siteLanguage]->translate($key, $args);
		}
		if (!$string) {
			$string = $key;
		}

		return $string;
	}

	/**
	 * Register a directory that holds translations
	 * 
	 * @param string $directory Absolute directory
	 * @return void
	 */
	public function registerTranslationDirectory($directory) {
		$this->translationDirectories[] = rtrim($directory, '/');
		if (!$this->cache) {
			$this->translations = array();
		}
	}

	/**
	 * Get the translation array of key => translation
	 * 
	 * @param string $language Two letter language code
	 * @return array
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function getTranslation($language) {
		$this->assertValidLanguage($language);
		$this->loadTranslation($language);
		return $this->translations[$language];
	}

	/**
	 * Replace or add to a translation
	 * 
	 * @param string $language    Two letter language code
	 * @param array  $translation Translation array
	 * @param bool   $replace     Whether to replace or add to a translation
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function setTranslation($language, array $translation, $replace = false) {
		$this->assertValidLanguage($language);
		if ($replace || !array_key_exists($language, $this->translations)) {
			$this->translations[$language] = $translation;
		} else {
			$this->translations[$language] = array_merge($this->translations[$language], $translation);
		}
	}

	/**
	 * Get translation arrays for all languages
	 * 
	 * @return array
	 */
	public function getAllTranslations() {
		$languages = $this->getAllLanguages();

		foreach ($languages as $language) {
			$this->loadTranslation($language);
		}
	
		return $this->translations;
	}

	/**
	 * Load a translation from files or cache
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 */
	protected function loadTranslation($language) {
		if (!array_key_exists($language, $this->translations)) {
			$translation = null;
			if ($this->cache) {
				$data = $this->cache->load($language);
				if ($data) {
					$translation = unserialize($data);
				}
			}

			if (!$translation) {
				$translation = $this->loadTranslationFromFiles($language);
			}

			$this->loadedLanguages[$language] = $translation;
			$this->translators[$language] = new Elgg_I18n_Translator($language, $translation);
		}
	}

	/**
	 * Load a translation array from language files
	 * 
	 * @param string $language Two letter language code
	 * @return array
	 */
	protected function loadTranslationFromFiles($language) {
		foreach ($this->translationDirectories as $dir) {
			$language_file = "$dir/$language.php";
			if (file_exists($language_file)) {
				$result = include_once($language_file);
				if ($result) {
					if (is_array($result)) {
						// Elgg 1.9 style language file
						$this->setTranslation($language, $result);
					} else {
						// Elgg 1.0-1.8 style (add_translation)
					}
				} else {
					// why would this fail
				}
			}
		}
	}

	/**
	 * Get all languages available
	 * 
	 * @return array
	 * @todo if cache is on, we could read this from cache
	 */
	protected function getAllLanguages() {
		$languages = array();
		foreach ($this->translationDirectories as $dir) {
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
	 * Assert that the language code is valid
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	protected function assertValidLanguage($language) {
		if (strlen($language) != 2) {
			throw new Elgg_I18n_InvalidLanguageException("$language is not a valid language code");
		}
	}
}
