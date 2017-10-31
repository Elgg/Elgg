<?php
namespace Elgg\I18n;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * A translator that does nothing except return the key that was requested.
 *
 * This translator is useful during development if you want to be able to
 * easily tell what the available keys are for changing the wording of UI elements.
 *
 * @since 1.10.0
 *
 * @access private
 */
final class NullTranslator extends Translator {
	
	/**
	 * {@inheritDoc}
	 */
	public function translate($key, $args = [], $lang = '') {
		return $key;
	}
}
