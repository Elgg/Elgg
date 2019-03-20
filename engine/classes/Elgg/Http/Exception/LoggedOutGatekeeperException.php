<?php

namespace Elgg\Http\Exception;

use Throwable;
use Elgg\GatekeeperException;

/**
 * Thrown when logged in but this isn't allowed
 */
class LoggedOutGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('loggedoutrequired');
		}
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		parent::__construct($message, $code, $previous);
	}
}
