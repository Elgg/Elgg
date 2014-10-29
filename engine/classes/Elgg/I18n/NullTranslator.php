<?php
namespace Elgg\I18n;

/**
 * A class that doesn't actually attempt translation.
 * 
 * Useful for debugging.
 * 
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 */
final class NullTranslator implements Translator {
	/** @inheritDoc */
	public function translate($key, $args = array(), Locale $language = NULL) {
		return $key;
	}
}