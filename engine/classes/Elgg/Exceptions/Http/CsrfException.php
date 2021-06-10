<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when CSRF tokens mismatch
 *
 * @since 4.0
 */
class CsrfException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		parent::__construct($message, $code, $previous);
	}
}
