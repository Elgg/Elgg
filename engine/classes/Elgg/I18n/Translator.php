<?php

namespace Elgg\I18n;

use Elgg\Config;
use Elgg\Includer;
use PluginException;

/**
 * Translator
 *
 * Use elgg()->translator
 *
 * @since 1.10.0
 */
class Translator {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var array
	 */
	private $translations = [];

	/**
	 * @var bool
	 */
	private $is_initialized = false;

	/**
	 * @var string
	 */
	private $defaultPath = null;

	/**
	 * @var string
	 */
	private $current_language = null;

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
	private $language_paths = [];

	/**
	 * @var bool
	 */
	private $was_reloaded = false;

	/**
	 * @var bool
	 */
	private $loaded_from_cache = false;

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 *
	 * @access private
	 * @internal
	 */
	public function __construct(Config $config) {
		$this->config = $config;
		$this->defaultPath = dirname(dirname(dirname(dirname(__DIR__)))) . "/languages/";
	}

	/**
	 * Check if translations were loaded from cache
	 *
	 * @return bool
	 * @access private
	 * @internal
	 */
	public function wasLoadedFromCache() {
		return $this->loaded_from_cache;
	}

	/**
	 * Get a map of all loaded translations
	 *
	 * @return array
	 */
	public function getLoadedTranslations() {
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
	 * @return string Either the translated string, the English string,
	 * or the original language string.
	 */
	public function translate($message_key, array $args = [], $language = "") {
		if (!is_string($message_key) || strlen($message_key) < 1) {
			_elgg_services()->logger->warning(
				'$message_key needs to be a string in ' . __METHOD__ . '(), ' . gettype($message_key) . ' provided'
			);
			return '';
		}

		if (!$language) {
			// no language provided, get current language
			// based on detection, user setting or site
			$language = $this->getCurrentLanguage();
		}

		$this->ensureTranslationsLoaded($language);

		// build language array for different trys
		// avoid dupes without overhead of array_unique
		$langs[$language] = true;

		// load site language
		$site_language = $this->config->language;
		if (!empty($site_language)) {
			$this->ensureTranslationsLoaded($site_language);

			$langs[$site_language] = true;
		}

		// ultimate language fallback
		$langs['en'] = true;

		// try to translate
		$notice = '';
		$string = $message_key;
		foreach (array_keys($langs) as $try_lang) {
			if (isset($this->translations[$try_lang][$message_key])) {
				$string = $this->translations[$try_lang][$message_key];

				// only pass through if we have arguments to allow backward compatibility
				// with manual sprintf() calls.
				if ($args) {
					$string = vsprintf($string, $args);
				}

				break;
			} else {
				$notice = sprintf(
					'Missing %s translation for "%s" language key',
					($try_lang === 'en') ? 'English' : $try_lang,
					$message_key
				);
			}
		}

		if ($notice) {
			_elgg_services()->logger->notice($notice);
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
	 * @param string $country_code   Standard country code (eg 'en', 'nl', 'es')
	 * @param array  $language_array Formatted array of strings
	 *
	 * @return bool Depending on success
	 */
	public function addTranslation($country_code, $language_array) {
		$country_code = strtolower($country_code);
		$country_code = trim($country_code);

		if (!is_array($language_array) || empty($language_array) || $country_code === "") {
			return false;
		}

		if (!isset($this->translations[$country_code])) {
			$this->translations[$country_code] = [];
		}

		// Note that we are using union operator instead of array_merge() due to performance implications
		$this->translations[$country_code] = $language_array + $this->translations[$country_code];

		return true;
	}

	/**
	 * Get the current system/user language or "en".
	 *
	 * @return string The language code for the site/user or "en" if not set
	 */
	public function getCurrentLanguage() {
		if (!isset($this->current_language)) {
			$this->current_language = $this->detectLanguage();
		}

		if (!$this->current_language) {
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
	public function setCurrentLanguage($language = null) {
		$this->current_language = $language;
	}

	/**
	 * Detect the current system/user language or false.
	 *
	 * @return false|string The language code (eg "en") or false if not set
	 * @access private
	 * @internal
	 */
	public function detectLanguage() {
		// detect from URL
		$url_lang = _elgg_services()->request->getParam('hl');
		if (!empty($url_lang)) {
			return $url_lang;
		}

		// check logged in user
		$user = _elgg_services()->session->getLoggedInUser();
		if (!empty($user) && !empty($user->language)) {
			return $user->language;
		}

		// get site setting
		$site_language = $this->config->language;
		if (!empty($site_language)) {
			return $site_language;
		}

		return false;
	}

	/**
	 * Load both core and plugin translations
	 *
	 * By default this loads only English and the language of the logged
	 * in user.
	 *
	 * The optional $language argument can be used to load translations
	 * on-demand in case we need to translate something to a language not
	 * loaded by default for the current request.
	 *
	 * @param string $language Language code
	 *
	 * @return void
	 *
	 * @access private
	 * @internal
	 */
	public function loadTranslations($language = null) {
		if (elgg_is_system_cache_enabled()) {
			$loaded = true;

			if ($language) {
				$languages = [$language];
			} else {
				$languages = array_unique(['en', $this->getCurrentLanguage()]);
			}

			foreach ($languages as $language) {
				$data = elgg_load_system_cache("{$language}.lang");
				if ($data) {
					$this->addTranslation($language, unserialize($data));
				} else {
					$loaded = false;
				}
			}

			if ($loaded) {
				$this->loaded_from_cache = true;
				$this->registerLanguagePath($this->defaultPath);
				$this->is_initialized = true;

				return;
			}
		}

		// load core translations from languages directory
		$this->registerTranslations($this->defaultPath, false, $language);

		// Plugin translation have already been loaded for the default
		// languages by ElggApplication::bootCore(), so there's no need
		// to continue unless loading a specific language on-demand
		if ($language) {
			$this->loadPluginTranslations($language);
		}
	}

	/**
	 * Load plugin translations for a language
	 *
	 * This is needed only if the current request uses a language
	 * that is neither English of the same as the language of the
	 * logged in user.
	 *
	 * @param string $language Language code
	 *
	 * @return void
	 * @throws PluginException
	 * @access private
	 * @internal
	 */
	private function loadPluginTranslations($language) {
		// Get active plugins
		$plugins = _elgg_services()->plugins->find('active');

		if (!$plugins) {
			// Active plugins were not found, so no need to register plugin translations
			return;
		}

		foreach ($plugins as $plugin) {
			$languages_path = "{$plugin->getPath()}languages/";

			if (!is_dir($languages_path)) {
				// This plugin doesn't have anything to translate
				continue;
			}

			$language_file = "{$languages_path}{$language}.php";

			if (!file_exists($language_file)) {
				// This plugin doesn't have translations for the requested language

				$name = $plugin->getDisplayName();
				_elgg_services()->logger->notice("Plugin $name is missing translations for $language language");

				continue;
			}

			// Register translations from the plugin languages directory
			if (!$this->registerTranslations($languages_path, false, $language)) {
				$msg = vsprintf(
					'Cannot register languages for plugin %s (guid: %s) at %s.',
					[$plugin->getID(), $plugin->guid, $languages_path]
				);

				throw PluginException::factory('CannotRegisterViews', $plugin, $msg);
			}
		}
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
	 * @access private
	 * @internal
	 */
	public function registerTranslations($path, $load_all = false, $language = null) {
		$path = \Elgg\Project\Paths::sanitize($path);
		
		// don't need to register translations as the folder is missing
		if (!is_dir($path)) {
			_elgg_services()->logger->info("No translations could be loaded from: $path");
			return true;
		}

		// Make a note of this path just in case we need to register this language later
		$this->registerLanguagePath($path);
		$this->is_initialized = true;

		_elgg_services()->logger->info("Translations loaded from: $path");

		if ($language) {
			$load_language_files = ["$language.php"];
			$load_all = false;
		} else {
			// Get the current language based on site defaults and user preference
			$current_language = $this->getCurrentLanguage();

			$load_language_files = [
				'en.php',
				"$current_language.php"
			];

			$load_language_files = array_unique($load_language_files);
		}

		$return = true;
		if ($handle = opendir($path)) {
			while (false !== ($language_file = readdir($handle))) {
				// ignore bad files
				if (substr($language_file, 0, 1) == '.' || substr($language_file, -4) !== '.php') {
					continue;
				}

				if (in_array($language_file, $load_language_files) || $load_all) {
					$return = $return && $this->includeLanguageFile($path . $language_file);
				}
			}
			closedir($handle);
		} else {
			_elgg_services()->logger->error("Could not open language path: $path");
			$return = false;
		}

		return $return;
	}

	/**
	 * Load cached or include a language file by its path
	 *
	 * @param string $path Path to file
	 * @return bool
	 * @access private
	 * @internal
	 */
	protected function includeLanguageFile($path) {
		$result = Includer::includeFile($path);
		
		if (is_array($result)) {
			$this->addTranslation(basename($path, '.php'), $result);
			return true;
		}

		return false;
	}

	/**
	 * Reload all translations from all registered paths.
	 *
	 * This is only called by functions which need to know all possible translations.
	 *
	 * @todo Better on demand loading based on language_paths array
	 *
	 * @return void
	 * @access private
	 * @internal
	 */
	public function reloadAllTranslations() {
		if ($this->was_reloaded) {
			return;
		}

		$languages = $this->getAvailableLanguages();
		
		foreach ($languages as $language) {
			if ($this->loaded_from_cache) {
				$data = elgg_load_system_cache("{$language}.lang");
				if ($data) {
					$this->addTranslation($language, unserialize($data));
				}
			} else {
				foreach ($this->getLanguagePaths() as $path) {
					$this->registerTranslations($path, false, $language);
				}
			}
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
	public function getInstalledTranslations($calculate_completeness = false) {
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
				$value .= " (" . $completeness . "% " . $this->translate('complete') . ")";
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
	public function getLanguageCompleteness($language) {

		if ($language == 'en') {
			return (float) 100;
		}

		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$en = count($this->translations['en']);

		$missing = $this->getMissingLanguageKeys($language);
		if ($missing) {
			$missing = count($missing);
		} else {
			$missing = 0;
		}

		$lang = $en - $missing;

		return round(($lang / $en) * 100, 2);
	}

	/**
	 * Return the translation keys missing from a given language,
	 * or those that are identical to the english version.
	 *
	 * @param string $language The language
	 *
	 * @return mixed
	 * @access private
	 * @internal
	 */
	public function getMissingLanguageKeys($language) {

		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$missing = [];

		foreach ($this->translations['en'] as $k => $v) {
			if ((!isset($this->translations[$language][$k]))
				|| ($this->translations[$language][$k] == $this->translations['en'][$k])) {
				$missing[] = $k;
			}
		}

		if (count($missing)) {
			return $missing;
		}

		return false;
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
	public function languageKeyExists($key, $language = 'en') {
		if (empty($key)) {
			return false;
		}

		$this->ensureTranslationsLoaded($language);

		if (!array_key_exists($language, $this->translations)) {
			return false;
		}

		return array_key_exists($key, $this->translations[$language]);
	}
	
	/**
	 * Returns an array of all available language keys. Triggers a hook to allow plugins to add/remove languages
	 *
	 * @return array
	 * @since 3.0
	 */
	public function getAvailableLanguages() {
		$languages = [];
		
		$allowed_languages = $this->getAllLanguageCodes();
		
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
		
		$languages = array_keys($languages);
				
		return _elgg_services()->hooks->trigger('languages', 'translations', [], $languages);
	}
	
	/**
	 * Registers a path for potential translation files
	 *
	 * @param string $path path to a folder that contains translation files
	 *
	 * @return void
	 * @access private
	 * @internal
	 */
	public function registerLanguagePath($path) {
		$this->language_paths[$path] = true;
	}
	
	/**
	 * Returns a unique array with locations of translation files
	 *
	 * @return array
	 * @access private
	 * @internal
	 */
	protected function getLanguagePaths() {
		return array_keys($this->language_paths);
	}

	/**
	 * Make sure translations are loaded
	 *
	 * @param string $language Language
	 * @return void
	 * @access private
	 * @internal
	 */
	private function ensureTranslationsLoaded($language) {
		if (!$this->is_initialized) {
			// this means we probably had an exception before translations were initialized
			$this->registerTranslations($this->defaultPath);
		}

		if (!isset($this->translations[$language])) {
			// The language being requested is not the same as the language of the
			// logged in user, so we will have to load it separately. (Most likely
			// we're sending a notification and the recipient is using a different
			// language than the logged in user.)
			$this->loadTranslations($language);
		}
	}

	/**
	 * Returns an array of language codes.
	 *
	 * @return array
	 * @access private
	 * @internal
	 */
	public static function getAllLanguageCodes() {
		return [
			"aa", // "Afar"
			"ab", // "Abkhazian"
			"af", // "Afrikaans"
			"am", // "Amharic"
			"ar", // "Arabic"
			"as", // "Assamese"
			"ay", // "Aymara"
			"az", // "Azerbaijani"
			"ba", // "Bashkir"
			"be", // "Byelorussian"
			"bg", // "Bulgarian"
			"bh", // "Bihari"
			"bi", // "Bislama"
			"bn", // "Bengali; Bangla"
			"bo", // "Tibetan"
			"br", // "Breton"
			"ca", // "Catalan"
			"cmn", // "Mandarin Chinese" // ISO 639-3
			"co", // "Corsican"
			"cs", // "Czech"
			"cy", // "Welsh"
			"da", // "Danish"
			"de", // "German"
			"dz", // "Bhutani"
			"el", // "Greek"
			"en", // "English"
			"eo", // "Esperanto"
			"es", // "Spanish"
			"et", // "Estonian"
			"eu", // "Basque"
			"eu_es", // "Basque (Spain)"
			"fa", // "Persian"
			"fi", // "Finnish"
			"fj", // "Fiji"
			"fo", // "Faeroese"
			"fr", // "French"
			"fy", // "Frisian"
			"ga", // "Irish"
			"gd", // "Scots / Gaelic"
			"gl", // "Galician"
			"gn", // "Guarani"
			"gu", // "Gujarati"
			"he", // "Hebrew"
			"ha", // "Hausa"
			"hi", // "Hindi"
			"hr", // "Croatian"
			"hu", // "Hungarian"
			"hy", // "Armenian"
			"ia", // "Interlingua"
			"id", // "Indonesian"
			"ie", // "Interlingue"
			"ik", // "Inupiak"
			"is", // "Icelandic"
			"it", // "Italian"
			"iu", // "Inuktitut"
			"iw", // "Hebrew (obsolete)"
			"ja", // "Japanese"
			"ji", // "Yiddish (obsolete)"
			"jw", // "Javanese"
			"ka", // "Georgian"
			"kk", // "Kazakh"
			"kl", // "Greenlandic"
			"km", // "Cambodian"
			"kn", // "Kannada"
			"ko", // "Korean"
			"ks", // "Kashmiri"
			"ku", // "Kurdish"
			"ky", // "Kirghiz"
			"la", // "Latin"
			"ln", // "Lingala"
			"lo", // "Laothian"
			"lt", // "Lithuanian"
			"lv", // "Latvian/Lettish"
			"mg", // "Malagasy"
			"mi", // "Maori"
			"mk", // "Macedonian"
			"ml", // "Malayalam"
			"mn", // "Mongolian"
			"mo", // "Moldavian"
			"mr", // "Marathi"
			"ms", // "Malay"
			"mt", // "Maltese"
			"my", // "Burmese"
			"na", // "Nauru"
			"ne", // "Nepali"
			"nl", // "Dutch"
			"no", // "Norwegian"
			"oc", // "Occitan"
			"om", // "(Afan) Oromo"
			"or", // "Oriya"
			"pa", // "Punjabi"
			"pl", // "Polish"
			"ps", // "Pashto / Pushto"
			"pt", // "Portuguese"
			"pt_br", // "Portuguese (Brazil)"
			"qu", // "Quechua"
			"rm", // "Rhaeto-Romance"
			"rn", // "Kirundi"
			"ro", // "Romanian"
			"ro_ro", // "Romanian (Romania)"
			"ru", // "Russian"
			"rw", // "Kinyarwanda"
			"sa", // "Sanskrit"
			"sd", // "Sindhi"
			"sg", // "Sangro"
			"sh", // "Serbo-Croatian"
			"si", // "Singhalese"
			"sk", // "Slovak"
			"sl", // "Slovenian"
			"sm", // "Samoan"
			"sn", // "Shona"
			"so", // "Somali"
			"sq", // "Albanian"
			"sr", // "Serbian"
			"sr_latin", // "Serbian (Latin)"
			"ss", // "Siswati"
			"st", // "Sesotho"
			"su", // "Sundanese"
			"sv", // "Swedish"
			"sw", // "Swahili"
			"ta", // "Tamil"
			"te", // "Tegulu"
			"tg", // "Tajik"
			"th", // "Thai"
			"ti", // "Tigrinya"
			"tk", // "Turkmen"
			"tl", // "Tagalog"
			"tn", // "Setswana"
			"to", // "Tonga"
			"tr", // "Turkish"
			"ts", // "Tsonga"
			"tt", // "Tatar"
			"tw", // "Twi"
			"ug", // "Uigur"
			"uk", // "Ukrainian"
			"ur", // "Urdu"
			"uz", // "Uzbek"
			"vi", // "Vietnamese"
			"vo", // "Volapuk"
			"wo", // "Wolof"
			"xh", // "Xhosa"
			"yi", // "Yiddish"
			"yo", // "Yoruba"
			"za", // "Zuang"
			"zh", // "Chinese"
			"zh_hans", // "Chinese Simplified"
			"zu", // "Zulu"
		];
	}

	/**
	 * Normalize a language code (e.g. from Transifex)
	 *
	 * @param string $code Language code
	 *
	 * @return string
	 * @access private
	 * @internal
	 */
	public static function normalizeLanguageCode($code) {
		$code = strtolower($code);
		$code = preg_replace('~[^a-z0-9]~', '_', $code);
		return $code;
	}
}