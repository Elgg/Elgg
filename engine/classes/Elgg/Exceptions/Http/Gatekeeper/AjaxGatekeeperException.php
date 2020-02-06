<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

use Elgg\Exceptions\Http\GatekeeperException;

/**
 * Thrown when the request is not a valid ajax request
 *
 * @since 4.0
 */
class AjaxGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ajax:not_is_xhr');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_BAD_REQUEST;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
