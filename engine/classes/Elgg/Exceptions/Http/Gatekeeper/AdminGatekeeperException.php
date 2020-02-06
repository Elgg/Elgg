<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

use Elgg\Exceptions\Http\GatekeeperException;

/**
 * Thrown when the logged in user is not an admin
 *
 * @since 4.0
 */
class AdminGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('adminrequired');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
