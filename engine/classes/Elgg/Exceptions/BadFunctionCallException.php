<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if a callback refers to an undefined function or if some arguments are missing
 *
 * @see https://www.php.net/manual/en/class.badfunctioncallexception.php
 * @since 4.3
 */
class BadFunctionCallException extends \BadFunctionCallException implements ExceptionInterface {
	
}
