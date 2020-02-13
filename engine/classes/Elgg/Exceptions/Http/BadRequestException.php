<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when request is malformatted
 *
 * @since 4.0
 */
class BadRequestException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('BadRequestException');
		}
		if (!$code) {
			$code = ELGG_HTTP_BAD_REQUEST;
		}
		parent::__construct($message, $code, $previous);
	}
}
