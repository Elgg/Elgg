<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown when an illegal index was requested. This represents errors that should be detected at compile time
 *
 * @see https://www.php.net/manual/en/class.outofrangeexception.php
 * @since 4.3
 */
class OutOfRangeException extends \OutOfRangeException implements ExceptionInterface {
	
}
