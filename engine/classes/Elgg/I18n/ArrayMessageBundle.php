<?php

namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Uses an array as a source for the message bundle.
 *
 * This is mostly useful for testing so we can configure translators
 * in-memory instead of going to the file system.
 *
 * @since 1.11
 * @internal
 */
final class ArrayMessageBundle implements MessageBundle {
	
	/** @var array */
	private $messages;
	
	/**
	 * Constructor
	 *
	 * @param array $messages Map of locales to maps of keys to message-templates
	 */
	public function __construct(array $messages) {
		$this->messages = $messages;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get($key, Locale $locale) {
		assert(is_string($key), '$key must be a string');
		
		if (!isset($this->messages["$locale"]) || !is_array($this->messages["$locale"])) {
			return null;
		}
		
		$messages = $this->messages["$locale"];
		if (!is_string($key) || !isset($messages[$key]) || !is_string($messages[$key])) {
			return null;
		}
		
		return new SprintfMessageTemplate($messages[$key]);
	}
}
