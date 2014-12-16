<?php
namespace Elgg\I18n;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 */
class NullTranslator extends Translator {
	/** @inheritDoc */
	public function translate($key, $args = array(), $lang = '') {
		return $key;
	}
}