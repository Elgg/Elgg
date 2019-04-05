<?php

namespace Elgg\I18n;

use Elgg\Config;

/**
 * Provides locale related features
 *
 * @internal
 * @since 3.0
 */
class LocaleService {
	
	/**
	 * @var Config
	 */
	protected $config;
	
	/**
	 * @var array
	 */
	protected $locale;
	
	/**
	 * Create new service
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(Config $config) {
		$this->config = $config;
		
		$this->initializeElggLocale();
	}
	
	/**
	 * Returns a list of supported laguage codes. Mostly based on ISO-639-1
	 *
	 * @return array
	 */
	public function getLanguageCodes() {
		return array_keys($this->locale);
	}
	
	/**
	 * Set the locale settings for a language key
	 *
	 * @param string $language the language key (eg. 'en')
	 * @param array  $locale   the locale settings (eg. ['en_US'])
	 *
	 * @return void
	 */
	public function setLocaleForLanguage(string $language, array $locale = []) {
		$this->locale[$language] = $locale;
	}
	
	/**
	 * Returns the configured locale settings for a given language key
	 *
	 * @param string $language the language key (eg. 'en')
	 *
	 * @return array
	 */
	public function getLocaleForLanguage(string $language) {
		return isset($this->locale[$language]) ? $this->locale[$language] : [];
	}
	
	/**
	 * Get the current locale
	 *
	 * @param int $category which locale setting should be returned
	 *
	 * @return array
	 */
	public function getLocale(int $category) {
		return (array) setlocale($category, 0);
	}
	
	/**
	 * Set the system locale to a new value, return the locale before the change
	 *
	 * @param int          $category which locale setting should be affected
	 * @param string|array $locale   the new locale
	 *
	 * @see setlocale()
	 * @see https://secure.php.net/manual/en/function.setlocale.php
	 *
	 * @return string[]
	 */
	public function setLocale(int $category, $locale) {
		$current = $this->getLocale($category);
		
		setlocale($category, $locale);
		
		return $current;
	}
	
	/**
	 * Set the locale from a language key. The key will be mapped to the configured locale settings
	 *
	 * @param int    $category which locale settings should be affected
	 * @param string $language the language te set the locale for (default: current language)
	 *
	 * @return string[]
	 */
	public function setLocaleFromLanguageKey(int $category, string $language = null) {
		if (!isset($language)) {
			$language = get_current_language();
		}
		
		$locale = [
			$language,
		];
		$locale += $this->getLocaleForLanguage($language);
		
		return $this->setLocale($category, $locale);
	}
	
	/**
	 * Initialize the locale mapping.
	 * This contains a language as key and the used locale settings as value
	 * eg. 'en' => ['en_US'] or 'nl' => ['nl_NL']
	 *
	 * The languages are based on ISO-639-1, but due to legacy it also contains other formats
	 *
	 * @return void
	 */
	private function initializeElggLocale() {
		$this->locale = [
			'aa' => [], // 'Afar'
			'ab' => [], // 'Abkhazian'
			'af' => [], // 'Afrikaans'
			'am' => [], // 'Amharic'
			'ar' => [], // 'Arabic'
			'as' => [], // 'Assamese'
			'ay' => [], // 'Aymara'
			'az' => [], // 'Azerbaijani'
			'ba' => [], // 'Bashkir'
			'be' => [], // 'Byelorussian'
			'bg' => [], // 'Bulgarian'
			'bh' => [], // 'Bihari'
			'bi' => [], // 'Bislama'
			'bn' => [], // 'Bengali; Bangla'
			'bo' => [], // 'Tibetan'
			'br' => [], // 'Breton'
			'ca' => [], // 'Catalan'
			'cmn' => [], // 'Mandarin Chinese' // ISO 639-3
			'co' => [], // 'Corsican'
			'cs' => [], // 'Czech'
			'cy' => [], // 'Welsh'
			'da' => [], // 'Danish'
			'de' => [], // 'German'
			'dz' => [], // 'Bhutani'
			'el' => [], // 'Greek'
			'en' => [], // 'English'
			'eo' => [], // 'Esperanto'
			'es' => [], // 'Spanish'
			'et' => [], // 'Estonian'
			'eu' => [], // 'Basque'
			'eu_es' => [], // 'Basque (Spain)'
			'fa' => [], // 'Persian'
			'fi' => [], // 'Finnish'
			'fj' => [], // 'Fiji'
			'fo' => [], // 'Faeroese'
			'fr' => [], // 'French'
			'fy' => [], // 'Frisian'
			'ga' => [], // 'Irish'
			'gd' => [], // 'Scots / Gaelic'
			'gl' => [], // 'Galician'
			'gn' => [], // 'Guarani'
			'gu' => [], // 'Gujarati'
			'he' => [], // 'Hebrew'
			'ha' => [], // 'Hausa'
			'hi' => [], // 'Hindi'
			'hr' => [], // 'Croatian'
			'hu' => [], // 'Hungarian'
			'hy' => [], // 'Armenian'
			'ia' => [], // 'Interlingua'
			'id' => [], // 'Indonesian'
			'ie' => [], // 'Interlingue'
			'ik' => [], // 'Inupiak'
			'is' => [], // 'Icelandic'
			'it' => [], // 'Italian'
			'iu' => [], // 'Inuktitut'
			'iw' => [], // 'Hebrew (obsolete)'
			'ja' => [], // 'Japanese'
			'ji' => [], // 'Yiddish (obsolete)'
			'jw' => [], // 'Javanese'
			'ka' => [], // 'Georgian'
			'kk' => [], // 'Kazakh'
			'kl' => [], // 'Greenlandic'
			'km' => [], // 'Cambodian'
			'kn' => [], // 'Kannada'
			'ko' => [], // 'Korean'
			'ks' => [], // 'Kashmiri'
			'ku' => [], // 'Kurdish'
			'ky' => [], // 'Kirghiz'
			'la' => [], // 'Latin'
			'ln' => [], // 'Lingala'
			'lo' => [], // 'Laothian'
			'lt' => [], // 'Lithuanian'
			'lv' => [], // 'Latvian/Lettish'
			'mg' => [], // 'Malagasy'
			'mi' => [], // 'Maori'
			'mk' => [], // 'Macedonian'
			'ml' => [], // 'Malayalam'
			'mn' => [], // 'Mongolian'
			'mo' => [], // 'Moldavian'
			'mr' => [], // 'Marathi'
			'ms' => [], // 'Malay'
			'mt' => [], // 'Maltese'
			'my' => [], // 'Burmese'
			'na' => [], // 'Nauru'
			'ne' => [], // 'Nepali'
			'nl' => [], // 'Dutch'
			'no' => [], // 'Norwegian'
			'oc' => [], // 'Occitan'
			'om' => [], // '(Afan) Oromo'
			'or' => [], // 'Oriya'
			'pa' => [], // 'Punjabi'
			'pl' => [], // 'Polish'
			'ps' => [], // 'Pashto / Pushto'
			'pt' => [], // 'Portuguese'
			'pt_br' => [], // 'Portuguese (Brazil)'
			'qu' => [], // 'Quechua'
			'rm' => [], // 'Rhaeto-Romance'
			'rn' => [], // 'Kirundi'
			'ro' => [], // 'Romanian'
			'ro_ro' => [], // 'Romanian (Romania)'
			'ru' => [], // 'Russian'
			'rw' => [], // 'Kinyarwanda'
			'sa' => [], // 'Sanskrit'
			'sd' => [], // 'Sindhi'
			'sg' => [], // 'Sangro'
			'sh' => [], // 'Serbo-Croatian'
			'si' => [], // 'Singhalese'
			'sk' => [], // 'Slovak'
			'sl' => [], // 'Slovenian'
			'sm' => [], // 'Samoan'
			'sn' => [], // 'Shona'
			'so' => [], // 'Somali'
			'sq' => [], // 'Albanian'
			'sr' => [], // 'Serbian'
			'sr_latin' => [], // 'Serbian (Latin)'
			'ss' => [], // 'Siswati'
			'st' => [], // 'Sesotho'
			'su' => [], // 'Sundanese'
			'sv' => [], // 'Swedish'
			'sw' => [], // 'Swahili'
			'ta' => [], // 'Tamil'
			'te' => [], // 'Tegulu'
			'tg' => [], // 'Tajik'
			'th' => [], // 'Thai'
			'ti' => [], // 'Tigrinya'
			'tk' => [], // 'Turkmen'
			'tl' => [], // 'Tagalog'
			'tn' => [], // 'Setswana'
			'to' => [], // 'Tonga'
			'tr' => [], // 'Turkish'
			'ts' => [], // 'Tsonga'
			'tt' => [], // 'Tatar'
			'tw' => [], // 'Twi'
			'ug' => [], // 'Uigur'
			'uk' => [], // 'Ukrainian'
			'ur' => [], // 'Urdu'
			'uz' => [], // 'Uzbek'
			'vi' => [], // 'Vietnamese'
			'vo' => [], // 'Volapuk'
			'wo' => [], // 'Wolof'
			'xh' => [], // 'Xhosa'
			'yi' => [], // 'Yiddish'
			'yo' => [], // 'Yoruba'
			'za' => [], // 'Zuang'
			'zh' => [], // 'Chinese'
			'zh_hans' => [], // 'Chinese Simplified'
			'zu' => [], // 'Zulu'
		];
		
		// merge $CONFIG mapping into the array
		// this can be set in settings.php
		if (is_array($this->config->language_to_locale_mapping)) {
			$this->locale = array_merge($this->locale, $this->config->language_to_locale_mapping);
		}
	}
}
