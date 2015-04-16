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
	function translate($message_key, $args = array(), $language = "") {
		
	
		static $CURRENT_LANGUAGE;
	
		// old param order is deprecated
		if (!is_array($args)) {
			elgg_deprecated_notice(
				'As of Elgg 1.8, the 2nd arg to elgg_echo() is an array of string replacements and the 3rd arg is the language.',
				1.8
			);
	
			$language = $args;
			$args = array();
		}
	
		if (!isset($this->CONFIG->translations)) {
			// this means we probably had an exception before translations were initialized
			$this->registerTranslations($this->defaultPath);
		}
	
		if (!$CURRENT_LANGUAGE) {
			$CURRENT_LANGUAGE = $this->getLanguage();
		}
		if (!$language) {
			$language = $CURRENT_LANGUAGE;
		}

		if (!isset($this->CONFIG->translations[$language])) {
			// The language being requested is not the same as the language of the
			// logged in user, so we will have to load it separately. (Most likely
			// we're sending a notification and the recipient is using a different
			// language than the logged in user.)
			_elgg_load_translations_for_language($language);
		}

		if (isset($this->CONFIG->translations[$language][$message_key])) {
			$string = $this->CONFIG->translations[$language][$message_key];
		} else if (isset($this->CONFIG->translations["en"][$message_key])) {
			$string = $this->CONFIG->translations["en"][$message_key];
			_elgg_services()->logger->notice(sprintf('Missing %s translation for "%s" language key', $language, $message_key));
		} else {
			$string = $message_key;
			_elgg_services()->logger->notice(sprintf('Missing English translation for "%s" language key', $message_key));
		}
	
		// only pass through if we have arguments to allow backward compatibility
		// with manual sprintf() calls.
		if ($args) {
			$string = vsprintf($string, $args);
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
	function addTranslation($country_code, $language_array) {
		
		if (!isset($this->CONFIG->translations)) {
			$this->CONFIG->translations = array();
		}
	
		$country_code = strtolower($country_code);
		$country_code = trim($country_code);
		if (is_array($language_array) && $country_code != "") {
			if (sizeof($language_array) > 0) { 
				if (!isset($this->CONFIG->translations[$country_code])) {
					$this->CONFIG->translations[$country_code] = $language_array;
				} else {
					$this->CONFIG->translations[$country_code] = $language_array + $this->CONFIG->translations[$country_code];
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Detect the current language being used by the current site or logged in user.
	 *
	 * @return string The language code for the site/user or "en" if not set
	 */
	function getCurrentLanguage() {
		$language = $this->getLanguage();
	
		if (!$language) {
			$language = 'en';
		}
	
		return $language;
	}
	
	/**
	 * Gets the current language in use by the system or user.
	 *
	 * @return string The language code (eg "en") or false if not set
	 */
	function getLanguage() {
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
	 * @access private
	 */
	function loadTranslations() {
		
	
		if ($this->CONFIG->system_cache_enabled) {
			$loaded = true;
			$languages = array_unique(array('en', $this->getCurrentLanguage()));
			foreach ($languages as $language) {
				$data = elgg_load_system_cache("$language.lang");
				if ($data) {
					$this->addTranslation($language, unserialize($data));
				} else {
					$loaded = false;
				}
			}
	
			if ($loaded) {
				$this->CONFIG->i18n_loaded_from_cache = true;
				// this is here to force 
				$this->CONFIG->language_paths[$this->defaultPath] = true;
				return;
			}
		}
	
		// load core translations from languages directory
		$this->registerTranslations($this->defaultPath);
	}
	
	
	
	/**
	 * When given a full path, finds translation files and loads them
	 *
	 * @param string $path     Full path
	 * @param bool   $load_all If true all languages are loaded, if
	 *                         false only the current language + en are loaded
	 *
	 * @return bool success
	 */
	function registerTranslations($path, $load_all = false) {
		$path = sanitise_filepath($path);
	
		// Make a note of this path just incase we need to register this language later
		if (!isset($this->CONFIG->language_paths)) {
			$this->CONFIG->language_paths = array();
		}
		$this->CONFIG->language_paths[$path] = true;
	
		// Get the current language based on site defaults and user preference
		$current_language = $this->getCurrentLanguage();
		_elgg_services()->logger->info("Translations loaded from: $path");

		// only load these files unless $load_all is true.
		$load_language_files = array(
			'en.php',
			"$current_language.php"
		);
	
		$load_language_files = array_unique($load_language_files);
	
		$handle = opendir($path);
		if (!$handle) {
			_elgg_services()->logger->error("Could not open language path: $path");
			return false;
		}
	
		$return = true;
		while (false !== ($language = readdir($handle))) {
			// ignore bad files
			if (substr($language, 0, 1) == '.' || substr($language, -4) !== '.php') {
				continue;
			}
	
			if (in_array($language, $load_language_files) || $load_all) {
				$result = include_once($path . $language);
				if ($result === false) {
					$return = false;
					continue;
				} elseif (is_array($result)) {
					$this->addTranslation(basename($language, '.php'), $result);
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
	function reloadAllTranslations() {
		
	
		static $LANG_RELOAD_ALL_RUN;
		if ($LANG_RELOAD_ALL_RUN) {
			return;
		}
	
		if ($this->CONFIG->i18n_loaded_from_cache) {
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
			foreach ($this->CONFIG->language_paths as $path => $dummy) {
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
	function getInstalledTranslations() {
		
	
		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();
	
		$installed = array();
		
		$admin_logged_in = _elgg_services()->session->isAdminLoggedIn();
	
		foreach ($this->CONFIG->translations as $k => $v) {
			$installed[$k] = $this->translate($k, array(), $k);
			if ($admin_logged_in && ($k != 'en')) {
				$completeness = $this->getLanguageCompleteness($k);
				if ($completeness < 100) {
					$installed[$k] .= " (" . $completeness . "% " . $this->translate('complete') . ")";
				}
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
	function getLanguageCompleteness($language) {
		
	
		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();
	
		$language = sanitise_string($language);
	
		$en = count($this->CONFIG->translations['en']);
	
		$missing = $this->getMissingLanguageKeys($language);
		if ($missing) {
			$missing = count($missing);
		} else {
			$missing = 0;
		}
	
		//$lang = count($this->CONFIG->translations[$language]);
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
	function getMissingLanguageKeys($language) {
		
	
		// Ensure that all possible translations are loaded
		$this->reloadAllTranslations();
	
		$missing = array();
	
		foreach ($this->CONFIG->translations['en'] as $k => $v) {
			if ((!isset($this->CONFIG->translations[$language][$k]))
			|| ($this->CONFIG->translations[$language][$k] == $this->CONFIG->translations['en'][$k])) {
				$missing[] = $k;
			}
		}
	
		if (count($missing)) {
			return $missing;
		}
	
		return false;
	}
	
	/**
	 * Check if a give language key exists
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
	
		if (($language !== 'en') && !array_key_exists($language, $this->CONFIG->translations)) {
			// Ensure that all possible translations are loaded
			$this->reloadAllTranslations();
		}
	
		if (!array_key_exists($language, $this->CONFIG->translations)) {
			return false;
		}
	
		return array_key_exists($key, $this->CONFIG->translations[$language]);
	}
	
	/**
	 * Returns an array of language codes.
	 *
	 * @return array
	 */
	function getAllLanguageCodes() {
		return array(
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
			"pt_br", // 'Brazilian Portuguese'
			"qu", // "Quechua"
			"rm", // "Rhaeto-Romance"
			"rn", // "Kirundi"
			"ro", // "Romanian"
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
			"zu", // "Zulu"
		);
	}

}