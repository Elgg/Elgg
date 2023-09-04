<?php

namespace Elgg\Developers;

use Elgg\I18n\Translator;

/**
 * A translator that appends the key to the translation.
 *
 * This translator is useful during development if you want to be able to
 * easily tell what the available keys are for changing the wording of UI elements.
 *
 * @since 4.1
 */
final class AppendTranslator extends Translator {
	
	/**
	 * {@inheritDoc}
	 */
	public function translate($message_key, $args = [], $language = ''): string {
		
		$result = parent::translate($message_key, $args, $language);
		
		return ($result === $message_key) ? $result : "{$result} ({$message_key})";
	}
}
