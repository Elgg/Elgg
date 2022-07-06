<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown to indicate range errors during program execution.
 * Normally this means there was an arithmetic error other than under/overflow.
 * This is the runtime version of DomainException.
 *
 * @see https://www.php.net/manual/en/class.rangeexception.php
 * @since 4.3
 */
class RangeException extends \RangeException implements ExceptionInterface {
	
}
