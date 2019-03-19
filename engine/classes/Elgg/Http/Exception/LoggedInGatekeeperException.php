<?php

namespace Elgg\Http\Exception;

use Throwable;
use Elgg\GatekeeperException;

/**
 * Thrown when the not logged in
 */
class LoggedInGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('loggedinrequired');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
