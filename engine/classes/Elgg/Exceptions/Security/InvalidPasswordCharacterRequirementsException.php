<?php

namespace Elgg\Exceptions\Security;

use Elgg\Exceptions\SecurityException;

/**
 * Indicate a password string doesn't meet the character requirements
 *
 * @since 3.2
 */
class InvalidPasswordCharacterRequirementsException extends SecurityException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('Security:InvalidPasswordCharacterRequirementsException');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
