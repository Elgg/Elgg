<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

use Elgg\Exceptions\Http\GatekeeperException;

/**
 * Thrown when registration isn't allowed
 *
 * @since 4.3
 */
class RegistrationAllowedGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('registerdisabled');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
