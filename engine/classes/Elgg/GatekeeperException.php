<?php

namespace Elgg;

use Throwable;

/**
 * Thrown when one of the gatekeepers prevents access
 */
class GatekeeperException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('GatekeeperException');
		}
		if (!$code) {
			$code = ELGG_HTTP_UNAUTHORIZED;
		}
		parent::__construct($message, $code, $previous);
	}
}
