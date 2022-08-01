<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if an error which can only be found on runtime occurs
 *
 * @see https://www.php.net/manual/en/class.runtimeexception.php
 * @since 4.3
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface {
	
}
