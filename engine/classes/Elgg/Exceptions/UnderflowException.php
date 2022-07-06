<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown when performing an invalid operation on an empty container, such as removing an element
 *
 * @see https://www.php.net/manual/en/class.underflowexception.php
 * @since 4.3
 */
class UnderflowException extends \UnderflowException implements ExceptionInterface {
	
}
