<?php

namespace Elgg\Http\Exception;

use Throwable;
use Elgg\GatekeeperException;

/**
 * Thrown when the logged in user is not an admin
 */
class AdminGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('adminrequired');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
