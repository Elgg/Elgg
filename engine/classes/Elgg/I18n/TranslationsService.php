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
class Elgg_I18n_TranslationsService {

	/** @var string */
	protected $siteLanguage;

	/** @var string */
	protected $userLanguage;

	/** @var Elgg_I18n_TranslationLoader */
	protected $loader;

	/** @var array */
	protected $loadedLanguages = array();

	/** @var array */
	protected $translators = array();

	/** @var bool */
	protected $on = true;

	/**
	 * Create a translation service
	 *
	 * @param Elgg_I18n_TranslationLoader $loader       Translation loader
	 * @param string                      $siteLanguage Two letter language code for default language
	 * @param string                      $userLanguage Two letter language code for default language
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function __construct($loader, $siteLanguage = 'en', $userLanguage = 'en') {
		Elgg_I18n_Translator::assertValidLanguage($siteLanguage);
		Elgg_I18n_Translator::assertValidLanguage($userLanguage);
		$this->siteLanguage = $siteLanguage;
		$this->userLanguage = $userLanguage;
		$this->loader = $loader;
	}

	/**
	 * Turn translation off
	 *
	 * @return void
	 */
	public function turnOff() {
		$this->on = false;
	}

	/**
	 * Turn translation on
	 *
	 * @return void
	 */
	public function turnOn() {
		$this->on = true;
	}

	/**
	 * Set the site language
	 *
	 * @param string $language Two letter language code
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function setSiteLanguage($language) {
		Elgg_I18n_Translator::assertValidLanguage($language);
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
		Elgg_I18n_Translator::assertValidLanguage($language);
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
	 * Register a directory that holds translations
	 *
	 * @param string $directory Absolute directory
	 * @return void
	 */
	public function registerTranslationDirectory($directory) {
		$this->loader->addDirectory($directory);
		$this->loadedLanguages = array();
	}

	/**
	 * Get the directories that contain translations
	 *
	 * The directory paths do not have a trailing slash
	 *
	 * @return array
	 */
	public function getTranslationDirectories() {
		return $this->loader->getDirectories();
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

		if ($this->on == false) {
			return $key;
		}

		$string = '';
		if ($language !== '') {
			Elgg_I18n_Translator::assertValidLanguage($language);
			$this->loadTranslation($language);
			$string = $this->translators[$language]->get($key, $args);
		}

		if (!$string) {
			$this->loadTranslation($this->userLanguage);
			$string = $this->translators[$this->userLanguage]->get($key, $args);
		}
		if (!$string) {
			$this->loadTranslation($this->siteLanguage);
			$string = $this->translators[$this->siteLanguage]->get($key, $args);
		}
		if (!$string) {
			$string = $key;
		}

		return $string;
	}

	/**
	 * Set the translator for a language
	 *
	 * @param Elgg_I18n_Translator $translator Translator for this language
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function setTranslator(Elgg_I18n_Translator $translator) {
		$this->translators[$translator->getLanguage()] = $translator;
	}

	/**
	 * Get the translator for this language
	 *
	 * @param string $language Two letter language code
	 * @return Elgg_I18n_Translator
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function getTranslator($language) {
		Elgg_I18n_Translator::assertValidLanguage($language);
		$this->loadTranslation($language);
		return $this->translators[$language];
	}

	/**
	 * Get translators for all languages
	 *
	 * The array is of the form language_code => translator
	 *
	 * @return Elgg_I18n_Translator[]
	 */
	public function getAllTranslators() {
		$languages = $this->loader->getAllLanguages();

		foreach ($languages as $language) {
			$this->loadTranslation($language);
		}

		return $this->translators;
	}

	/**
	 * Load a translation
	 *
	 * @param string $language Two letter language code
	 * @return void
	 */
	protected function loadTranslation($language) {
		if (!in_array($language, $this->loadedLanguages)) {
			$translation = $this->loader->loadTranslation($language);
			$this->loadedLanguages[] = $language;
			$this->translators[$language] = new Elgg_I18n_Translator($language, $translation);
		}
	}
}
