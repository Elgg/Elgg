<?php
namespace Elgg\I18n;

use \Elgg\Logger;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 */
final class DefaultTranslator implements Translator {
	
	/** @var Locale */
	private $siteLocale;
	
	/** @var Locale */
	private $userLocale;
	
	/** @var Loader */
	private $loader;
	
	/** @var Logger */
	private $logger;
	
	/** @var array */
	private $loadedLocales = array();
	
	/** @var array */
	private $translators = array();
	
	/**
	 * Create a translation service
	 *
	 * @param Loader $loader Translation loader
	 * @param Logger $logger Logger
	 */
	public function __construct(Loader $loader, Logger $logger) {
		$this->loader = $loader;
		$this->logger = $logger;
		
		$this->siteLocale = Locale::parse('en');
		$this->userLocale = Locale::parse('en');
	}
	
	/**
	 * Set the site locale
	 *
	 * @param Locale $locale The locale
	 * 
	 * @return void
	 */
	public function setSiteLocale(Locale $locale) {
		$this->siteLocale = $locale;
	}
	
	/**
	 * Set the user's locale
	 *
	 * @param Locale $locale The locale
	 * 
	 * @return void
	 */
	public function setUserLocale(Locale $locale) {
		$this->userLocale = $locale;
	}
	
	/** @inheritDoc */
	public function translate($key, $args = array(), Locale $locale = NULL) {
		
		if (isset($locale)) {
			$fallbacks["$locale"] = array(
				'locale' => $locale,
				'message' => 'Missing specified locale\'s (%s) translation for "%s" language key',
			);
		}
		
		if (!isset($fallbacks["{$this->userLocale}"])) {
			$fallbacks["{$this->userLocale}"] = array(
				'locale' => $this->userLocale,
				'message' => 'Missing user\'s locale (%s) translation for "%s" language key',
			);
		}
		
		if (!isset($fallbacks["{$this->siteLocale}"])) {
			$fallbacks["{$this->siteLocale}"] = array(
				'locale' => $this->siteLocale,
				'Missing site\'s locale (%s) translation for "%s" language key',
			);
		}
		
		if (!isset($fallbacks['en'])) {
			$fallbacks['en'] = array(
				'locale' => Locale::parse('en'),
				'message' => 'Missing %s translation for "%s" language key',
			);
		}
		
		$string = '';
		
		foreach ($fallbacks as $params) {
			$notice = $params['message'];
			$locale = $params['locale'];
			
			$string = $this->getTranslationMap($locale)->get($key, $args);
			if ($string) {
				break;
			}
			
			$this->logger->notice(sprintf($notice, "$locale", $key));
		}
		
		if (!$string) {
			$string = $key;
		}
		
		return $string;
	}
	
	/**
	 * Lazily load a set of locales.
	 *
	 * @param Locale $locale The locale
	 * 
	 * @return Map
	 */
	private function getTranslationMap(Locale $locale) {
		if (isset($this->translators["$locale"])) {
			return $this->translators["$locale"];
		}
		
		$translation = $this->loader->loadTranslation($locale);
		$this->translators["$locale"] = $translation;
		return $translation;
	}
}
