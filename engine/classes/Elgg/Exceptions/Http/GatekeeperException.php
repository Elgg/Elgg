<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when one of the gatekeepers prevents access
 *
 * @since 4.0
 */
class GatekeeperException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('GatekeeperException');
		}
		if (!$code) {
			$code = ELGG_HTTP_UNAUTHORIZED;
		}
		parent::__construct($message, $code, $previous);
	}
}
