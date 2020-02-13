<?php

namespace Elgg\Exceptions\Security;

use Elgg\Exceptions\SecurityException;

/**
 * Indicate a password string doesn't meet the minimal length requirements
 *
 * @since 3.2
 */
class InvalidPasswordLengthException extends SecurityException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('Security:InvalidPasswordLengthException', [elgg_get_config('min_password_length')]);
		}
		
		parent::__construct($message, $code, $previous);
	}
}
