<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the client isn't authorized for the request
 *
 * @since 6.2
 */
class UnauthorizedException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('UnauthorizedException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_UNAUTHORIZED;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
