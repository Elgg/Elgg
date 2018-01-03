<?php

namespace Elgg;

use Throwable;

/**
 * Thrown when request is malformatted
 */
class BadRequestException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('BadRequestException');
		}
		if (!$code) {
			$code = ELGG_HTTP_BAD_REQUEST;
		}
		parent::__construct($message, $code, $previous);
	}
}
