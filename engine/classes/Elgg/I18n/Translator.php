<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since 1.10.0
 */
class Translator {

	/**
	 * Global Elgg configuration
	 *
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Initializes new translator
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
		$this->defaultPath = dirname(dirname(dirname(dirname(__DIR__)))) . "/languages/";
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
	public function translate($message_key, $args = [], $language = "") {
		// TODO find a way to cache getLanguage() and get rid of this
		static $CURRENT_LANGUAGE;
		
		if (!is_string($message_key) || strlen($message_key) < 1) {
			_elgg_services()->logger->warn(
				'$message_key needs to be a string in ' . __METHOD__ . '(), ' . gettype($message_key) . ' provided'
			);
			return '';
		}
		
		// old param order is deprecated
		if (!is_array($args)) {
			elgg_deprecated_notice(
				'As of Elgg 1.8, the 2nd arg to elgg_echo() is an array of string replacements and the 3rd arg is the language.',
				1.8
			);

			$language = $args;
			$args = array();
		}

		if (!$CURRENT_LANGUAGE) {
			$CURRENT_LANGUAGE = $this->getCurrentLanguage();
		}
		if (!$language) {
			$language = $CURRENT_LANGUAGE;
		}

		$this->ensureTranslationsLoaded($language);

		$notice = '';
		$string = $message_key;

		// avoid dupes without overhead of array_unique
		$langs[$language] = true;
		$langs['en'] = true;

		foreach (array_keys($langs) as $try_lang) {
			if (isset($GLOBALS['_ELGG']->translations[$try_lang][$message_key])) {
				$string = $GLOBALS['_ELGG']->translations[$try_lang][$message_key];

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

		if (!isset($GLOBALS['_ELGG']->translations)) {
			$GLOBALS['_ELGG']->translations = array();
		}

		$country_code = strtolower($country_code);
		$country_code = trim($country_code);
		if (is_array($language_array) && $country_code != "") {
			if (sizeof($language_array) > 0) {
				if (!isset($GLOBALS['_ELGG']->translations[$country_code])) {
					$GLOBALS['_ELGG']->translations[$country_code] = $language_array;
				} else {
					$GLOBALS['_ELGG']->translations[$country_code] = $language_array + $GLOBALS['_ELGG']->translations[$country_code];
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Get the current system/user language or "en".
	 *
	 * @return string The language code for the site/user or "en" if not set
	 */
	public function getCurrentLanguage() {
		$language = $this->detectLanguage();

		if (!$language) {
			$language = 'en';
		}

		return $language;
	}

	/**
	 * Detect the current system/user language or false.
	 *
	 * @return string The language code (eg "en") or false if not set
	 */
	public function detectLanguage() {
		$url_lang = _elgg_services()->input->get('hl');
		if ($url_lang) {
			return $url_lang;
		}

		$user = _elgg_services()->session->getLoggedInUser();
		$language = false;

		if (($user) && ($user->language)) {
			$language = $user->language;
		}

		if ((!$language) && (isset($this->CONFIG->language)) && ($this->CONFIG->language)) {
			$language = $this->CONFIG->language;
		}

		if ($language) {
			return $language;
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
	 * @access private
	 */
	public function loadTranslations($language = null) {
		if (elgg_is_system_cache_enabled()) {
			$loaded = true;

			if ($language) {
				$languages = array($language);
			} else {
				$languages = array_unique(array('en', $this->getCurrentLanguage()));
			}

			foreach ($languages as $language) {
				$data = elgg_load_system_cache("$language.lang");
				if ($data) {
					$this->addTranslation($language, unserialize($data));
				} else {
					$loaded = false;
				}
			}

			if ($loaded) {
				$GLOBALS['_ELGG']->i18n_loaded_from_cache = true;
				// this is here to force
				$GLOBALS['_ELGG']->language_paths[$this->defaultPath] = true;
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
	 * @return void
	 * @throws \PluginException
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

				$name = $plugin->getFriendlyName();
				_elgg_services()->logger->notice("Plugin $name is missing translations for $language language");

				continue;
			}

			// Register translations from the plugin languages directory
			if (!$this->registerTranslations($languages_path, false, $language)) {
				throw new \PluginException(sprintf('Cannot register languages for plugin %s (guid: %s) at %s.',
					array($plugin->getID(), $plugin->guid, $languages_path)));
			}
		}
	}

	/**
	 * Registers translations in a directory assuming the standard plugin layout.
	 *
	 * @param string $path Without the trailing slash.
	 *
	 * @return bool Success
	 */
	public function registerPluginTranslations($path) {
		$languages_path = rtrim($path, "\\/") . "/languages";

		// don't need to have translations
		if (!is_dir($languages_path)) {
			return true;
		}

		return $this->registerTranslations($languages_path);
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
	 */
	public function registerTranslations($path, $load_all = false, $language = null) {
		$path = sanitise_filepath($path);

		// Make a note of this path just incase we need to register this language later
		if (!isset($GLOBALS['_ELGG']->language_paths)) {
			$GLOBALS['_ELGG']->language_paths = array();
		}
		$GLOBALS['_ELGG']->language_paths[$path] = true;

		_elgg_services()->logger->info("Translations loaded from: $path");

		if ($language) {
			$load_language_files = array("$language.php");
			$load_all = false;
		} else {
			// Get the current language based on site defaults and user preference
			$current_language = $this->getCurrentLanguage();

			$load_language_files = array(
				'en.php',
				"$current_language.php"
			);

			$load_language_files = array_unique($load_language_files);
		}

		$handle = opendir($path);
		if (!$handle) {
			_elgg_services()->logger->error("Could not open language path: $path");
			return false;
		}

		$return = true;
		while (false !== ($language_file = readdir($handle))) {
			// ignore bad files
			if (substr($language_file, 0, 1) == '.' || substr($language_file, -4) !== '.php') {
				continue;
			}

			if (in_array($language_file, $load_language_files) || $load_all) {
				$result = include_once($path . $language_file);
				if ($result === false) {
					$return = false;
					continue;
				} elseif (is_array($result)) {
					$this->addTranslation(basename($language_file, '.php'), $result);
				}
			}
		}

		return $return;
	}

	/**
	 * Reload all translations from all registered paths.
	 *
	 * This is only called by functions which need to know all possible translations.
	 *
	 * @todo Better on demand loading based on language_paths array
	 *
	 * @return void
	 */
	public function reloadAllTranslations() {


		static $LANG_RELOAD_ALL_RUN;
		if ($LANG_RELOAD_ALL_RUN) {
			return;
		}

		if ($GLOBALS['_ELGG']->i18n_loaded_from_cache) {
			$cache = elgg_get_system_cache();
			$cache_dir = $cache->getVariable("cache_path");
			$filenames = elgg_get_file_list($cache_dir, array(), array(), array(".lang"));
			foreach ($filenames as $filename) {
				// Look for files matching for example 'en.lang', 'cmn.lang' or 'pt_br.lang'.
				// Note that this regex is just for the system cache. The original language
				// files are allowed to have uppercase letters (e.g. pt_BR.php).
				if (preg_match('/(([a-z]{2,3})(_[a-z]{2})?)\.lang$/', $filename, $matches)) {
					$language = $matches[1];
					$data = elgg_load_system_cache("$language.lang");
					if ($data) {
						$this->addTranslation($language, unserialize($data));
					}
				}
			}
		} else {
			foreach ($GLOBALS['_ELGG']->language_paths as $path => $dummy) {
				$this->registerTranslations($path, true);
			}
		}

		$LANG_RELOAD_ALL_RUN = true;
	}

	/**
	 * Return an array of installed translations as an associative
	 * array "two letter code" => "native language name".
	 *
	 * @return array
	 */
	public function getInstalledTranslations() {


		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$installed = array();

		$admin_logged_in = _elgg_services()->session->isAdminLoggedIn();

		foreach ($GLOBALS['_ELGG']->translations as $k => $v) {
			if ($this->languageKeyExists($k, $k)) {
				$lang = $this->translate($k, [], $k);
			} else {
				$lang = $this->translate($k);
			}
			
			$installed[$k] = $lang;
			
			if (!$admin_logged_in || ($k === 'en')) {
				continue;
			}
			
			$completeness = $this->getLanguageCompleteness($k);
			if ($completeness < 100) {
				$installed[$k] .= " (" . $completeness . "% " . $this->translate('complete') . ")";
			}
		}

		return $installed;
	}

	/**
	 * Return the level of completeness for a given language code (compared to english)
	 *
	 * @param string $language Language
	 *
	 * @return int
	 */
	public function getLanguageCompleteness($language) {


		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$language = sanitise_string($language);

		$en = count($GLOBALS['_ELGG']->translations['en']);

		$missing = $this->getMissingLanguageKeys($language);
		if ($missing) {
			$missing = count($missing);
		} else {
			$missing = 0;
		}

		//$lang = count($GLOBALS['_ELGG']->translations[$language]);
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
	 */
	public function getMissingLanguageKeys($language) {


		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();

		$missing = array();

		foreach ($GLOBALS['_ELGG']->translations['en'] as $k => $v) {
			if ((!isset($GLOBALS['_ELGG']->translations[$language][$k]))
			|| ($GLOBALS['_ELGG']->translations[$language][$k] == $GLOBALS['_ELGG']->translations['en'][$k])) {
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
	function languageKeyExists($key, $language = 'en') {
		if (empty($key)) {
			return false;
		}

		$this->ensureTranslationsLoaded($language);

		if (!array_key_exists($language, $GLOBALS['_ELGG']->translations)) {
			return false;
		}

		return array_key_exists($key, $GLOBALS['_ELGG']->translations[$language]);
	}

	/**
	 * Make sure translations are loaded
	 *
	 * @param string $language Language
	 * @return void
	 */
	private function ensureTranslationsLoaded($language) {
		if (!isset($GLOBALS['_ELGG']->translations)) {
			// this means we probably had an exception before translations were initialized
			$this->registerTranslations($this->defaultPath);
		}

		if (!isset($GLOBALS['_ELGG']->translations[$language])) {
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
	 */
	public static function normalizeLanguageCode($code) {
		$code = strtolower($code);
		$code = preg_replace('~[^a-z0-9]~', '_', $code);
		return $code;
	}
}