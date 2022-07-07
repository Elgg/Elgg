<?php

namespace Elgg\Exceptions;

/**
 * Error exception, when converting an error into an exception
 *
 * @see set_error_handler()
 * @see https://www.php.net/manual/en/class.errorexception.php
 * @since 4.3
 */
class ErrorException extends \ErrorException implements ExceptionInterface {
	
}
