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
 * @internal
 */
final class AppendTranslator extends Translator {
	
	/**
	 * {@inheritDoc}
	 */
	public function translate($key, $args = [], $lang = '') {
		
		$result = parent::translate($key, $args, $lang);
		
		return ($result === $key) ? $result : "{$result} ({$key})";
	}
}
