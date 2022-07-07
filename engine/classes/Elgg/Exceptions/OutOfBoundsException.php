<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if a value is not a valid key. This represents errors that cannot be detected at compile time
 *
 * @see https://www.php.net/manual/en/class.outofboundsexception.php
 * @since 4.3
 */
class OutOfBoundsException extends \OutOfBoundsException implements ExceptionInterface {
	
}
