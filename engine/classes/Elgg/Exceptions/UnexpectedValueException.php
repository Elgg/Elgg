<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if a value does not match with a set of values.
 * Typically this happens when a function calls another function and expects the return value to
 * be of a certain type or value not including arithmetic or buffer related errors.
 *
 * @see https://www.php.net/manual/en/class.unexpectedvalueexception.php
 * @since 4.3
 */
class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface {
	
}
