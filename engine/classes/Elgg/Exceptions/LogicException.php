<?php

namespace Elgg\Exceptions;

/**
 * Exception that represents error in the program logic. This kind of exception should lead directly to a fix in your code
 *
 * @see https://www.php.net/manual/en/class.logicexception.php
 * @since 4.3
 */
class LogicException extends \LogicException implements ExceptionInterface {
	
}
