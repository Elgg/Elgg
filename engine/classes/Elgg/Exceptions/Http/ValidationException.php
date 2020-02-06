<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Action validation exception
 *
 * @since 4.0
 */
class ValidationException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$code) {
			$code = ELGG_HTTP_BAD_REQUEST;
		}
		parent::__construct($message, $code, $previous);
	}
}
