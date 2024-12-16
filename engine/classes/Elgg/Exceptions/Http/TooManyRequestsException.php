<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the client sends to many requests in a given period
 *
 * @since 6.2
 */
class TooManyRequestsException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('TooManyRequestsException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_TOO_MANY_REQUESTS;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
