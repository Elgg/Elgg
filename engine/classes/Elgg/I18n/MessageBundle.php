<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * TODO(ewinslow): Have this extend Table(row=string, column=Locale, value=?MessageTemplate)
 *                 if we ever support a Table data structure.
 *
 * @since 1.11
 *
 * @access private
 */
interface MessageBundle {
	
	/**
	 * Fetches the translatable message associated with the given key
	 *
	 * @param string $key    String identifier for the message
	 * @param Locale $locale Locale in which the message is written
	 *
	 * @return ?MessageTemplate The message object or null if not found.
	 */
	public function get($key, Locale $locale);
}
