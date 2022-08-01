<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if a callback refers to an undefined method or if some arguments are missing
 *
 * @see https://www.php.net/manual/en/class.badmethodcallexception.php
 * @since 4.3
 */
class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface {
	
}
