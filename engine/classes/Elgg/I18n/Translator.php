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
	public static function getAllLanguageCodes() {
		// Generated by /.scripts/transifex/parse.js
		return [
			"ach", // Acoli
			"ady", // Adyghe
			"af", // Afrikaans
			"af_ZA", // Afrikaans (South Africa)
			"ak", // Akan
			"sq", // Albanian
			"sq_AL", // Albanian (Albania)
			"aln", // Albanian Gheg
			"am", // Amharic
			"am_ET", // Amharic (Ethiopia)
			"ar", // Arabic
			"ar_EG", // Arabic (Egypt)
			"ar_SA", // Arabic (Saudi Arabia)
			"ar_SD", // Arabic (Sudan)
			"ar_SY", // Arabic (Syria)
			"ar_AA", // Arabic (Unitag)
			"an", // Aragonese
			"hy", // Armenian
			"hy_AM", // Armenian (Armenia)
			"as", // Assamese
			"as_IN", // Assamese (India)
			"ast", // Asturian
			"ast_ES", // Asturian (Spain)
			"az", // Azerbaijani
			"az_Arab", // Azerbaijani (Arabic)
			"az_AZ", // Azerbaijani (Azerbaijan)
			"az_IR", // Azerbaijani (Iran)
			"az_latin", // Azerbaijani (Latin)
			"bal", // Balochi
			"ba", // Bashkir
			"eu", // Basque
			"eu_ES", // Basque (Spain)
			"bar", // Bavarian
			"be", // Belarusian
			"be_BY", // Belarusian (Belarus)
			"be_tarask", // Belarusian (Tarask)
			"bn", // Bengali
			"bn_BD", // Bengali (Bangladesh)
			"bn_IN", // Bengali (India)
			"brx", // Bodo
			"bs", // Bosnian
			"bs_BA", // Bosnian (Bosnia and Herzegovina)
			"br", // Breton
			"bg", // Bulgarian
			"bg_BG", // Bulgarian (Bulgaria)
			"my", // Burmese
			"my_MM", // Burmese (Myanmar)
			"ca", // Catalan
			"ca_ES", // Catalan (Spain)
			"ca_valencia", // Catalan (Valencian)
			"ceb", // Cebuano
			"tzm", // Central Atlas Tamazight
			"hne", // Chhattisgarhi
			"cgg", // Chiga
			"zh", // Chinese
			"zh_CN", // Chinese (China)
			"zh_CN_GB2312", // Chinese (China) (GB2312)
			"gan", // Chinese (Gan)
			"hak", // Chinese (Hakka)
			"zh_HK", // Chinese (Hong Kong)
			"czh", // Chinese (Huizhou)
			"cjy", // Chinese (Jinyu)
			"lzh", // Chinese (Literary)
			"cmn", // Chinese (Mandarin)
			"mnp", // Chinese (Min Bei)
			"cdo", // Chinese (Min Dong)
			"nan", // Chinese (Min Nan)
			"czo", // Chinese (Min Zhong)
			"cpx", // Chinese (Pu-Xian)
			"zh_Hans", // Chinese Simplified
			"zh_TW", // Chinese (Taiwan)
			"zh_TW_Big5", // Chinese (Taiwan) (Big5)
			"zh_Hant", // Chinese Traditional
			"wuu", // Chinese (Wu)
			"hsn", // Chinese (Xiang)
			"yue", // Chinese (Yue)
			"cv", // Chuvash
			"ksh", // Colognian
			"kw", // Cornish
			"co", // Corsican
			"crh", // Crimean Turkish
			"hr", // Croatian
			"hr_HR", // Croatian (Croatia)
			"cs", // Czech
			"cs_CZ", // Czech (Czech Republic)
			"da", // Danish
			"da_DK", // Danish (Denmark)
			"dv", // Divehi
			"doi", // Dogri
			"nl", // Dutch
			"nl_BE", // Dutch (Belgium)
			"nl_NL", // Dutch (Netherlands)
			"dz", // Dzongkha
			"dz_BT", // Dzongkha (Bhutan)
			"en", // English
			"en_AU", // English (Australia)
			"en_AT", // English (Austria)
			"en_BD", // English (Bangladesh)
			"en_BE", // English (Belgium)
			"en_CA", // English (Canada)
			"en_CL", // English (Chile)
			"en_CZ", // English (Czech Republic)
			"en_ee", // English (Estonia)
			"en_FI", // English (Finland)
			"en_DE", // English (Germany)
			"en_GH", // English (Ghana)
			"en_HK", // English (Hong Kong)
			"en_HU", // English (Hungary)
			"en_IN", // English (India)
			"en_IE", // English (Ireland)
			"en_lv", // English (Latvia)
			"en_lt", // English (Lithuania)
			"en_NL", // English (Netherlands)
			"en_NZ", // English (New Zealand)
			"en_NG", // English (Nigeria)
			"en_PK", // English (Pakistan)
			"en_PL", // English (Poland)
			"en_RO", // English (Romania)
			"en_SK", // English (Slovakia)
			"en_ZA", // English (South Africa)
			"en_LK", // English (Sri Lanka)
			"en_SE", // English (Sweden)
			"en_CH", // English (Switzerland)
			"en_GB", // English (United Kingdom)
			"en_US", // English (United States)
			"myv", // Erzya
			"eo", // Esperanto
			"et", // Estonian
			"et_EE", // Estonian (Estonia)
			"fo", // Faroese
			"fo_FO", // Faroese (Faroe Islands)
			"fil", // Filipino
			"fi", // Finnish
			"fi_FI", // Finnish (Finland)
			"frp", // Franco-Provençal (Arpitan)
			"fr", // French
			"fr_BE", // French (Belgium)
			"fr_CA", // French (Canada)
			"fr_FR", // French (France)
			"fr_CH", // French (Switzerland)
			"fur", // Friulian
			"ff", // Fulah
			"ff_SN", // Fulah (Senegal)
			"gd", // Gaelic, Scottish
			"gl", // Galician
			"gl_ES", // Galician (Spain)
			"lg", // Ganda
			"ka", // Georgian
			"ka_GE", // Georgian (Georgia)
			"de", // German
			"de_AT", // German (Austria)
			"de_DE", // German (Germany)
			"de_CH", // German (Switzerland)
			"el", // Greek
			"el_GR", // Greek (Greece)
			"kl", // Greenlandic
			"gu", // Gujarati
			"gu_IN", // Gujarati (India)
			"gun", // Gun
			"ht", // Haitian (Haitian Creole)
			"ht_HT", // Haitian (Haitian Creole) (Haiti)
			"ha", // Hausa
			"haw", // Hawaiian
			"he", // Hebrew
			"he_IL", // Hebrew (Israel)
			"hi", // Hindi
			"hi_IN", // Hindi (India)
			"hu", // Hungarian
			"hu_HU", // Hungarian (Hungary)
			"is", // Icelandic
			"is_IS", // Icelandic (Iceland)
			"io", // Ido
			"ig", // Igbo
			"ilo", // Iloko
			"id", // Indonesian
			"id_ID", // Indonesian (Indonesia)
			"ia", // Interlingua
			"iu", // Inuktitut
			"ga", // Irish
			"ga_IE", // Irish (Ireland)
			"it", // Italian
			"it_IT", // Italian (Italy)
			"it_CH", // Italian (Switzerland)
			"ja", // Japanese
			"ja_JP", // Japanese (Japan)
			"jv", // Javanese
			"kab", // Kabyle
			"kn", // Kannada
			"kn_IN", // Kannada (India)
			"pam", // Kapampangan
			"ks", // Kashmiri
			"ks_IN", // Kashmiri (India)
			"csb", // Kashubian
			"kk", // Kazakh
			"kk_Arab", // Kazakh (Arabic)
			"kk_Cyrl", // Kazakh (Cyrillic)
			"kk_KZ", // Kazakh (Kazakhstan)
			"kk_latin", // Kazakh (Latin)
			"km", // Khmer
			"km_KH", // Khmer (Cambodia)
			"rw", // Kinyarwanda
			"ky", // Kirgyz
			"tlh", // Klingon
			"kok", // Konkani
			"ko", // Korean
			"ko_KR", // Korean (Korea)
			"ku", // Kurdish
			"ku_IQ", // Kurdish (Iraq)
			"lad", // Ladino
			"lo", // Lao
			"lo_LA", // Lao (Laos)
			"ltg", // Latgalian
			"la", // Latin
			"lv", // Latvian
			"lv_LV", // Latvian (Latvia)
			"lez", // Lezghian
			"lij", // Ligurian
			"li", // Limburgian
			"ln", // Lingala
			"lt", // Lithuanian
			"lt_LT", // Lithuanian (Lithuania)
			"jbo", // Lojban
			"en_lolcat", // LOLCAT English
			"lmo", // Lombard
			"dsb", // Lower Sorbian
			"nds", // Low German
			"lb", // Luxembourgish
			"mk", // Macedonian
			"mk_MK", // Macedonian (Macedonia)
			"mai", // Maithili
			"mg", // Malagasy
			"ms", // Malay
			"ml", // Malayalam
			"ml_IN", // Malayalam (India)
			"ms_MY", // Malay (Malaysia)
			"mt", // Maltese
			"mt_MT", // Maltese (Malta)
			"mni", // Manipuri
			"mi", // Maori
			"arn", // Mapudungun
			"mr", // Marathi
			"mr_IN", // Marathi (India)
			"mh", // Marshallese
			"mw1", // Mirandese
			"mn", // Mongolian
			"mn_MN", // Mongolian (Mongolia)
			"nah", // Nahuatl
			"nv", // Navajo
			"nr", // Ndebele, South
			"nap", // Neapolitan
			"ne", // Nepali
			"ne_NP", // Nepali (Nepal)
			"nia", // Nias
			"nqo", // N'ko
			"se", // Northern Sami
			"nso", // Northern Sotho
			"no", // Norwegian
			"nb", // Norwegian Bokmål
			"nb_NO", // Norwegian Bokmål (Norway)
			"no_NO", // Norwegian (Norway)
			"nn", // Norwegian Nynorsk
			"nn_NO", // Norwegian Nynorsk (Norway)
			"ny", // Nyanja
			"oc", // Occitan (post 1500)
			"or", // Oriya
			"or_IN", // Oriya (India)
			"om", // Oromo
			"os", // Ossetic
			"pfl", // Palatinate German
			"pa", // Panjabi (Punjabi)
			"pa_IN", // Panjabi (Punjabi) (India)
			"pap", // Papiamento
			"fa", // Persian
			"fa_AF", // Persian (Afghanistan)
			"fa_IR", // Persian (Iran)
			"pms", // Piemontese
			"en_pirate", // Pirate English
			"pl", // Polish
			"pl_PL", // Polish (Poland)
			"pt", // Portuguese
			"pt_BR", // Portuguese (Brazil)
			"pt_PT", // Portuguese (Portugal)
			"ps", // Pushto
			"ro", // Romanian
			"ro_RO", // Romanian (Romania)
			"rm", // Romansh
			"ru", // Russian
			"ru_ee", // Russian (Estonia)
			"ru_lv", // Russian (Latvia)
			"ru_lt", // Russian (Lithuania)
			"ru_petr1708", // Russian Petrine orthography
			"ru_RU", // Russian (Russia)
			"sah", // Sakha (Yakut)
			"sm", // Samoan
			"sa", // Sanskrit
			"sat", // Santali
			"sc", // Sardinian
			"sco", // Scots
			"sr", // Serbian
			"sr_Ijekavian", // Serbian (Ijekavian)
			"sr_ijekavianlatin", // Serbian (Ijekavian Latin)
			"sr_latin", // Serbian (Latin)
			"sr_RS_latin", // Serbian (Latin) (Serbia)
			"sr_RS", // Serbian (Serbia)
			"sn", // Shona
			"scn", // Sicilian
			"szl", // Silesian
			"sd", // Sindhi
			"si", // Sinhala
			"si_LK", // Sinhala (Sri Lanka)
			"sk", // Slovak
			"sk_SK", // Slovak (Slovakia)
			"sl", // Slovenian
			"sl_SI", // Slovenian (Slovenia)
			"so", // Somali
			"son", // Songhay
			"st", // Sotho, Southern
			"st_ZA", // Sotho, Southern (South Africa)
			"sma", // Southern Sami
			"es", // Spanish
			"es_AR", // Spanish (Argentina)
			"es_BO", // Spanish (Bolivia)
			"es_CL", // Spanish (Chile)
			"es_CO", // Spanish (Colombia)
			"es_CR", // Spanish (Costa Rica)
			"es_DO", // Spanish (Dominican Republic)
			"es_EC", // Spanish (Ecuador)
			"es_SV", // Spanish (El Salvador)
			"es_GT", // Spanish (Guatemala)
			"es_419", // Spanish (Latin America)
			"es_MX", // Spanish (Mexico)
			"es_NI", // Spanish (Nicaragua)
			"es_PA", // Spanish (Panama)
			"es_PY", // Spanish (Paraguay)
			"es_PE", // Spanish (Peru)
			"es_PR", // Spanish (Puerto Rico)
			"es_ES", // Spanish (Spain)
			"es_US", // Spanish (United States)
			"es_UY", // Spanish (Uruguay)
			"es_VE", // Spanish (Venezuela)
			"su", // Sundanese
			"sw", // Swahili
			"sw_KE", // Swahili (Kenya)
			"ss", // Swati
			"sv", // Swedish
			"sv_FI", // Swedish (Finland)
			"sv_SE", // Swedish (Sweden)
			"tl", // Tagalog
			"tl_PH", // Tagalog (Philippines)
			"tg", // Tajik
			"tg_TJ", // Tajik (Tajikistan)
			"tzl", // Talossan
			"ta", // Tamil
			"ta_IN", // Tamil (India)
			"ta_LK", // Tamil (Sri-Lanka)
			"tt", // Tatar
			"te", // Telugu
			"te_IN", // Telugu (India)
			"tet", // Tetum (Tetun)
			"th", // Thai
			"th_TH", // Thai (Thailand)
			"bo", // Tibetan
			"bo_CN", // Tibetan (China)
			"ti", // Tigrinya
			"to", // Tongan
			"ts", // Tsonga
			"tn", // Tswana
			"tr", // Turkish
			"tr_TR", // Turkish (Turkey)
			"tk", // Turkmen
			"tk_TM", // Turkmen (Turkmenistan)
			"udm", // Udmurt
			"ug", // Uighur
			"ug_Arab", // Uighur (Arabic)
			"ug_Cyrl", // Uighur (Cyrillic)
			"ug_Latin", // Uighur (Latin)
			"uk", // Ukrainian
			"uk_UA", // Ukrainian (Ukraine)
			"vmf", // Upper Franconian
			"hsb", // Upper Sorbian
			"ur", // Urdu
			"ur_PK", // Urdu (Pakistan)
			"uz", // Uzbek
			"uz_Arab", // Uzbek (Arabic)
			"uz_Cyrl", // Uzbek (Cyrillic)
			"uz_Latn", // Uzbek (Latin)
			"uz_UZ", // Uzbek (Uzbekistan)
			"ve", // Venda
			"vec", // Venetian
			"vi", // Vietnamese
			"vi_VN", // Vietnamese (Viet Nam)
			"vls", // Vlaams
			"wa", // Walloon
			"war", // Wáray-Wáray
			"cy", // Welsh
			"cy_GB", // Welsh (United Kingdom)
			"fy", // Western Frisian
			"fy_NL", // Western Frisian (Netherlands)
			"wo", // Wolof
			"wo_SN", // Wolof (Senegal)
			"xh", // Xhosa
			"yi", // Yiddish
			"yo", // Yoruba
			"zu", // Zulu
			"zu_ZA", // Zulu (South Africa)
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
		$code = preg_replace('~[^a-zA-Z0-9]~', '_', $code);
		return $code;
	}
}