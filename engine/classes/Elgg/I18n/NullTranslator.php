<?php

namespace Elgg\I18n;

/**
 * A translator that does nothing except return the key that was requested.
 *
 * This translator is useful during development if you want to be able to
 * easily tell what the available keys are for changing the wording of UI elements.
 *
 * @since 1.10.0
 * @internal
 */
final class NullTranslator extends Translator {
	
	/**
	 * {@inheritDoc}
	 */
	public function translate($message_key, $args = [], $language = ''): string {
		return $message_key;
	}
}
