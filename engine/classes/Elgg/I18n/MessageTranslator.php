<?php

namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 * @since 1.11
 */
class MessageTranslator implements TranslatorInterface {
	
	// TODO Maybe this should be an array of locales to provide multiple fallbacks?
	/** @var Locale */
	private $defaultLocale;
	
	/** @var MessageBundle */
	private $messages;
	
	/**
	 * Constructor
	 *
	 * @param Locale        $defaultLocale The fallback locale
	 * @param MessageBundle $messages      Messages that this translator is aware of
	 */
	public function __construct(Locale $defaultLocale, MessageBundle $messages) {
		$this->defaultLocale = $defaultLocale;
		$this->messages = $messages;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function translate($key, array $args = [], Locale $locale = null) {
		$locales = [
			$locale,
			$this->defaultLocale,
			Locale::parse('en'),
		];
		
		foreach ($locales as $locale) {
			if (!$locale) {
				continue;
			}
			
			$message = $this->messages->get($key, $locale);
			
			if ($message) {
				return $message->format($args);
			}
		}
		
		return $key;
	}
}
