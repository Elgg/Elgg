<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

use Elgg\Exceptions\Http\GatekeeperException;

/**
 * Thrown when the not logged in
 *
 * @since 4.0
 */
class LoggedInGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('loggedinrequired');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
