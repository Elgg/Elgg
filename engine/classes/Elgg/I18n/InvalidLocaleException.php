<?php

namespace Elgg\I18n;

/**
 * Thrown when a string is passed to Locale::parse that isn't recognized as a locale.
 * 
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 * 
 * @access private
 */
class InvalidLocaleException extends \InvalidArgumentException {}
