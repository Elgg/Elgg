<?php

namespace Elgg\Exceptions;

/**
 * Exception thrown if a value does not adhere to a defined valid data domain
 *
 * @see https://www.php.net/manual/en/class.domainexception.php
 * @since 4.3
 */
class DomainException extends \DomainException implements ExceptionInterface {
	
}
