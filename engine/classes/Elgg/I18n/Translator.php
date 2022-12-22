<?php

namespace Elgg\I18n;

use Elgg\Config;
use Elgg\Includer;
use Elgg\Traits\Loggable;

/**
 * Translator
 *
 * Use elgg()->translator
 *
 * @since 1.10.0
 */
class Translator {

	use Loggable;
	
	protected Config $config;

	protected LocaleService $locale;

	protected array $translations = [];

	protected string $defaultPath;

	protected ?string $current_language = null;

	protected array $allowed_languages;

	/**
	 * Paths to scan for autoloading languages.
	 *
	 * Languages are automatically loaded for the site or
	 * user's default language.  Plugins can extend or override strings.
	 * language_paths is an array of paths to scan for PHP files matching
	 * the default language.  The order of paths is determined by the plugin load order,
	 * with later entries overriding earlier.  Language files within these paths are
	 * named as the two-letter ISO 639-1 country codes for the language they represent.
	 *
	 * @link http://en.wikipedia.org/wiki/ISO_639-1
	 *
	 * @var array (paths are keys)
	 */
	protected array $language_paths = [];

	protected bool $was_reloaded = false;

	/**
	 * Constructor
	 *
	 * @param Config        $config Elgg config
	 * @param LocaleService $locale locale service
	 */
	public function __construct(Config $config, LocaleService $locale) {
		$this->config = $config;
		$this->locale = $locale;
		
		$this->defaultPath = dirname(__DIR__, 4) . '/languages/';
		
		$this->registerLanguagePath($this->defaultPath);
	}

	/**
	 * Get a map of all loaded translations
	 *
	 * @return array
	 */
	public function getLoadedTranslations(): array {
		return $this->translations;
	}

	/**
	 * Given a message key, returns an appropriately translated full-text string
	 *
	 * @param string $message_key The short message code
	 * @param array  $args        An array of arguments to pass through vsprintf().
	 * @param string $language    Optionally, the standard language code
	 *                            (defaults to site/user default, then English)
	 *
	 * @return string Either the translated string, the English string or the original language string.
	 */
	public function translate(string $message_key, array $args = [], string $language = ''): string {
		if (\Elgg\Values::isEmpty($message_key)) {
			return '';
		}

		// if no language provided, get current language based on detection, user setting or site
		$language = $language ?: $this->getCurrentLanguage();

		// build language array for different trys
		// avoid dupes without overhead of array_unique
		$langs = [
			$language => true,
		];

		// load site language
		$site_language = $this->config->language;
		if (!empty($site_language)) {
			$langs[$site_language] = true;
		}

		// ultimate language fallback
		$langs['en'] = true;
		
		$langs = array_intersect_key($langs, array_flip($this->getAllowedLanguages()));

		// try to translate
		$string = $message_key;
		foreach (array_keys($langs) as $try_lang) {
			$this->ensureTranslationsLoaded($try_lang);
			
			if (isset($this->translations[$try_lang][$message_key])) {
				$string = $this->translations[$try_lang][$message_key];

				// only pass through if we have arguments to allow backward compatibility
				// with manual sprintf() calls.
				if (!empty($args)) {
					try {
						$string = vsprintf($string, $args);
						
						if ($string === false) {
							$string = $message_key;
							
							$this->getLogger()->warning("Translation error for key '{$message_key}': Too few arguments provided (" . var_export($args, true) . ')');
						}
					} catch (\ValueError $e) {
						// PHP 8 throws errors
						$string = $message_key;
						
						$this->getLogger()->warning("Translation error for key '{$message_key}': " . $e->getMessage());
					}
				}

				break;
			} else {
				$message = sprintf(
					'Missing %s translation for "%s" language key',
					($try_lang === 'en') ? 'English' : $try_lang,
					$message_key
				);
				
				if ($try_lang === 'en') {
					$this->getLogger()->notice($message);
				} else {
					$this->getLogger()->info($message);
				}
			}
		}

		return $string;
	}

	/**
	 * Add a translation.
	 *
	 * Translations are arrays in the Zend Translation array format, eg:
	 *
	 *	$english = array('message1' => 'message1', 'message2' => 'message2');
	 *  $german = array('message1' => 'Nachricht1','message2' => 'Nachricht2');
	 *
	 * @param string $country_code               Standard country code (eg 'en', 'nl', 'es')
	 * @param array  $language_array             Formatted array of strings
	 * @param bool   $ensure_translations_loaded Ensures translations are loaded before adding the language array (default: true)
	 *
	 * @return bool Depending on success
	 */
	public function addTranslation(string $country_code, array $language_array, bool $ensure_translations_loaded = true): bool {
		$country_code = trim(strtolower($country_code));

		if (empty($language_array) || $country_code === '') {
			return false;
		}

		if (!isset($this->translations[$country_code])) {
			$this->translations[$country_code] = [];
			
			if ($ensure_translations_loaded) {
				// make sure all existing paths are included first before adding language arrays
				$this->loadTranslations($country_code);
			}
		}

		// Note that we are using union operator instead of array_merge() due to performance implications
		$this->translations[$country_code] = $language_array + $this->translations[$country_code];

		return true;
	}

	/**
	 * Get the current system/user language or 'en'.
	 *
	 * @return string
	 */
	public function getCurrentLanguage(): string {
		if (!isset($this->current_language)) {
			$this->current_language = $this->detectLanguage();
		}

		if (empty($this->current_language)) {
			$this->current_language = 'en';
		}

		return $this->current_language;
	}

	/**
	 * Sets current system language
	 *
	 * @param string $language Language code
	 *
	 * @return void
	 */
	public function setCurrentLanguage(string $language = null): void {
		$this->current_language = $language;
	}

	/**
	 * Detect the current system/user language or false.
	 *
	 * @return string The language code (eg 'en')
	 *
	 * @internal
	 */
	public function detectLanguage(): string {
		// detect from URL
		$url_lang = _elgg_services()->request->getParam('hl');
		$user = _elgg_services()->session_manager->getLoggedInUser();
		
		if (!empty($url_lang)) {
			// store language for logged out users
			if (empty($user)) {
				$cookie = new \ElggCookie('language');
				$cookie->value = $url_lang;
				elgg_set_cookie($cookie);
			}
			
			return $url_lang;
		}
		
		// detect from cookie
		$cookie = _elgg_services()->request->cookies->get('language');
		if (!empty($cookie)) {
			return $cookie;
		}

		// check logged in user
		if (!empty($user) && !empty($user->language)) {
			return $user->language;
		}

		// detect from browser if not logged in
		if ($this->config->language_detect_from_browser) {
			$browserlangs = _elgg_services()->request->getLanguages();
			if (!empty($browserlangs)) {
				$browserlang = explode('_', $browserlangs[0]);
				
				return $browserlang[0];
			}
		}
		
		// get site setting or empty string if not set in config
		return (string) $this->config->language;
	}

	/**
	 * Ensures all needed translations are loaded
	 *
	 * This loads only English and the language of the logged in user.
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function bootTranslations(): void {
		$languages = array_unique(['en', $this->getCurrentLanguage()]);
		
		foreach ($languages as $language) {
			$this->loadTranslations($language);
		}
	}

	/**
	 * Load both core and plugin translations
	 *
	 * The $language argument can be used to load translations
	 * on-demand in case we need to translate something to a language not
	 * loaded by default for the current request.
	 *
	 * @param string $language Language code
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function loadTranslations(string $language): void {
		$data = elgg_load_system_cache("{$language}.lang");
		if (is_array($data)) {
			$this->addTranslation($language, $data, false);
			return;
		}
		
		foreach ($this->getLanguagePaths() as $path) {
			$this->registerTranslations($path, false, $language);
		}
			
		$translations = elgg_extract($language, $this->translations, []);
		elgg_save_system_cache("{$language}.lang", $translations);
	}

	/**
	 * When given a full path, finds translation files and loads them
	 *
	 * @param string $path     Full path
	 * @param bool   $load_all If true all languages are loaded, if
	 *                         false only the current language + en are loaded
	 * @param string $language Language code
	 *
	 * @return bool success
	 *
	 * @internal
	 */
	public function registerTranslations(string $path, bool $load_all = false, string $language = null): bool {
		$path = \Elgg\Project\Paths::sanitize($path);
		
		// don't need to register translations as the folder is missing
		if (!is_dir($path)) {
			$this->getLogger()->info("No translations could be loaded from: {$path}");
			return true;
		}

		// Make a note of this path just in case we need to register this language later
		$this->registerLanguagePath($path);

		$this->getLogger()->info("Translations loaded from: {$path}");

		if ($language) {
			$load_language_files = ["{$language}.php"];
			$load_all = false;
		} else {
			// Get the current language based on site defaults and user preference
			$current_language = $this->getCurrentLanguage();

			$load_language_files = [
				'en.php',
				"{$current_language}.php"
			];

			$load_language_files = array_unique($load_language_files);
		}

		$handle = opendir($path);
		if (empty($handle)) {
			$this->getLogger()->error("Could not open language path: {$path}");
			return false;
		}
		
		$return = true;
		while (($language_file = readdir($handle)) !== false) {
			// ignore bad files
			if (str_starts_with($language_file, '.') || !str_ends_with($language_file, '.php')) {
				continue;
			}

			if (in_array($language_file, $load_language_files) || $load_all) {
				$return = $return && $this->includeLanguageFile($path . $language_file);
			}
		}
		
		closedir($handle);

		return $return;
	}

	/**
	 * Load cached or include a language file by its path
	 *
	 * @param string $path Path to file
	 * @return bool
	 *
	 * @internal
	 */
	protected function includeLanguageFile(string $path): bool {
		$result = Includer::includeFile($path);
		
		if (is_array($result)) {
			$this->addTranslation(basename($path, '.php'), $result);
			return true;
		}
		
		$this->getLogger()->warning("Language file did not return an array: {$path}");
		
		return false;
	}

	/**
	 * Reload all translations from all registered paths.
	 *
	 * This is only called by functions which need to know all possible translations.
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function reloadAllTranslations(): void {
		if ($this->was_reloaded) {
			return;
		}

		$languages = $this->getAvailableLanguages();
		
		foreach ($languages as $language) {
			$this->ensureTranslationsLoaded($language);
		}
		
		_elgg_services()->events->triggerAfter('reload', 'translations');

		$this->was_reloaded = true;
	}

	/**
	 * Return an array of installed translations as an associative
	 * array "two letter code" => "native language name".
	 *
	 * @param boolean $calculate_completeness Set to true if you want a completeness postfix added to the language text
	 *
	 * @return array
	 */
	public function getInstalledTranslations(bool $calculate_completeness = false): array {
		if ($calculate_completeness) {
			// Ensure that all possible translations are loaded
			$this->reloadAllTranslations();
		}
		
		$result = [];

		$languages = $this->getAvailableLanguages();
		foreach ($languages as $language) {
			if ($this->languageKeyExists($language, $language)) {
				$value = $this->translate($language, [], $language);
			} else {
				$value = $this->translate($language);
			}
			
			if (($language !== 'en') && $calculate_completeness) {
				$completeness = $this->getLanguageCompleteness($language);
				$value .= ' (' . $completeness . '% ' . $this->translate('complete') . ')';
			}
			
			$result[$language] = $value;
		}
		
		natcasesort($result);
			
		return $result;
	}

	/**
	 * Return the level of completeness for a given language code (compared to english)
	 *
	 * @param string $language Language
	 *
	 * @return float
	 */
	public function getLanguageCompleteness(string $language): float {
		if ($language === 'en') {
			return (float) 100;
		}

		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$en = count($this->translations['en']);

		$missing = count($this->getMissingLanguageKeys($language));

		$lang = $en - $missing;

		return round(($lang / $en) * 100, 2);
	}

	/**
	 * Return the translation keys missing from a given language,
	 * or those that are identical to the english version.
	 *
	 * @param string $language The language
	 *
	 * @return array
	 *
	 * @internal
	 */
	public function getMissingLanguageKeys(string $language): array {
		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$missing = [];

		foreach ($this->translations['en'] as $k => $v) {
			if (!isset($this->translations[$language][$k]) || ($this->translations[$language][$k] === $this->translations['en'][$k])) {
				$missing[] = $k;
			}
		}

		return $missing;
	}

	/**
	 * Check if a given language key exists
	 *
	 * @param string $key      The translation key
	 * @param string $language The specific language to check
	 *
	 * @return bool
	 * @since 1.11
	 */
	public function languageKeyExists(string $key, string $language = 'en'): bool {
		if (\Elgg\Values::isEmpty($key)) {
			return false;
		}

		$this->ensureTranslationsLoaded($language);

		if (!array_key_exists($language, $this->translations)) {
			return false;
		}

		return array_key_exists($key, $this->translations[$language]);
	}
	
	/**
	 * Returns an array of all available language keys. Triggers an event to allow plugins to add/remove languages
	 *
	 * @return array
	 * @since 3.0
	 */
	public function getAvailableLanguages(): array {
		$languages = [];
		
		$allowed_languages = $this->locale->getLanguageCodes();
		
		foreach ($this->getLanguagePaths() as $path) {
			try {
				$iterator = new \DirectoryIterator($path);
			} catch (\Exception $e) {
				continue;
			}
			
			foreach ($iterator as $file) {
				if ($file->isDir()) {
					continue;
				}
				
				if ($file->getExtension() !== 'php') {
					continue;
				}
				
				$language = $file->getBasename('.php');
				if (empty($language) || !in_array($language, $allowed_languages)) {
					continue;
				}
				
				$languages[$language] = true;
			}
		}
						
		return _elgg_services()->events->triggerResults('languages', 'translations', [], array_keys($languages));
	}
	
	/**
	 * Returns an array of allowed languages as configured by the site admin
	 *
	 * @return string[]
	 * @since 3.3
	 */
	public function getAllowedLanguages(): array {
		if (isset($this->allowed_languages)) {
			return $this->allowed_languages;
		}
		
		$allowed_languages = $this->config->allowed_languages;
		if (!empty($allowed_languages)) {
			$allowed_languages = explode(',', $allowed_languages);
			$allowed_languages = array_filter(array_unique($allowed_languages));
		} else {
			$allowed_languages = $this->getAvailableLanguages();
		}
		
		if (!in_array($this->config->language, $allowed_languages)) {
			// site language is always allowed
			$allowed_languages[] = $this->config->language;
		}
		
		if (!in_array('en', $allowed_languages)) {
			// 'en' language is always allowed
			$allowed_languages[] = 'en';
		}
		
		$this->allowed_languages = $allowed_languages;
		
		return $allowed_languages;
	}
	
	/**
	 * Registers a path for potential translation files
	 *
	 * @param string $path path to a folder that contains translation files
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function registerLanguagePath(string $path): void {
		if (isset($this->language_paths[$path])) {
			return;
		}
		
		if (!is_dir($path)) {
			return;
		}
		
		$this->language_paths[$path] = true;
	}
	
	/**
	 * Returns a unique array with locations of translation files
	 *
	 * @return array
	 *
	 * @internal
	 */
	public function getLanguagePaths(): array {
		return array_keys($this->language_paths);
	}

	/**
	 * Make sure translations are loaded
	 *
	 * @param string $language Language
	 *
	 * @return void
	 */
	protected function ensureTranslationsLoaded(string $language): void {
		if (isset($this->translations[$language])) {
			return;
		}
		
		// The language being requested is not the same as the language of the
		// logged in user, so we will have to load it separately. (Most likely
		// we're sending a notification and the recipient is using a different
		// language than the logged in user.)
		$this->loadTranslations($language);
	}
}
